<?php

require_once(__DIR__ . '/../Traits/WithTransactionWizard.php');

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Models\Transaction;
use xGrz\PayU\Services\ConfigService;

class IncomingNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function createFakeTransaction(PaymentStatus $paymentStatus = null, int $amount = 1000)
    {
        return Transaction::create([
            'amount' => $amount,
            'status' => $paymentStatus ?? PaymentStatus::INITIALIZED,
            'payu_order_id' => '9GLFZSPNG9240409GUEST000P01',
            'payload' => [],
            'link' => 'https://google.com'
        ]);
    }

    private function fakeIncomingOrderNotificationData(Transaction $transaction = null, PaymentStatus $payloadStatus = null): array
    {
        $transaction = $transaction ?? self::createFakeTransaction();
        $payload = [
            'order' => [
                'orderId' => $transaction->payu_order_id,
                'extOrderId' => $transaction->id,
                'status' => $payloadStatus?->name ?? PaymentStatus::PENDING->name,
                'products' => [
                    [
                        'name' => 'Product1',
                        'unitPrice' => 10000,
                        'quantity' => 1
                    ]
                ]
            ]
        ];
        $hash = hash('md5', json_encode(($payload)) . xGrz\PayU\Facades\Config::getSignatureKey());
        $headers = [
            'Content-Type' => 'application/json',
            'openpayu-signature' => 'sender=checkout;signature=' . $hash . ';algorithm=MD5;content=DOCUMENT'
        ];
        $uri = route(config('payu.routing.notification.route_name'), $transaction->id);
        return [
            'transaction' => $transaction,
            'payload' => $payload,
            'headers' => $headers,
            'uri' => $uri
        ];
    }

    private function fakeIncomingRefundNotificationData(Transaction $transaction = null, RefundStatus $status = null): array
    {
        $transaction = $transaction ?? self::createFakeTransaction();
        $payload = [
            'orderId' => $transaction->payu_order_id,
            'extOrderId' => $transaction->id,
            'refund' => [
                'refundId' => 'STAOADHGADOJDAODA',
                'extRefundId' => $transaction->id,
                'amount' => 1000,
                'currencyCode' => 'PLN',
                'status' => $status?->name ?? RefundStatus::FINALIZED->name,
                'refundDate' => now(),
                'reasonDescription' => 'RMA',
            ]
        ];
        $hash = hash('md5', json_encode(($payload)) . xGrz\PayU\Facades\Config::getSignatureKey());
        $headers = [
            'Content-Type' => 'application/json',
            'openpayu-signature' => 'sender=checkout;signature=' . $hash . ';algorithm=MD5;content=DOCUMENT'
        ];
        $uri = route(config('payu.routing.notification.route_name'), $transaction->id);
        return [
            'transaction' => $transaction,
            'payload' => $payload,
            'headers' => $headers,
            'uri' => $uri
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
        Config::set('payu.api.use_sandbox', true);
        Config::set('payu.api.oAuthClientId', ConfigService::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_ID']);
        Config::set('payu.api.oAuthClientSecret', ConfigService::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_SECRET']);
    }

    public function test_order_signature_verification_success_when_data_not_modified()
    {
        $fakeIncomingOrderNotificationData = $this->fakeIncomingOrderNotificationData();
        $response = $this
            ->withHeaders($fakeIncomingOrderNotificationData['headers'])
            ->json('post', $fakeIncomingOrderNotificationData['uri'], $fakeIncomingOrderNotificationData['payload']);

        $response->assertStatus(200);
    }

    public function test_order_signature_verification_fail_when_data_modified()
    {
        $fakeIncomingOrderNotificationData = $this->fakeIncomingOrderNotificationData();
        $fakeIncomingOrderNotificationData['payload']['order']['status'] = 'COMPLETED';
        $response = $this
            ->withHeaders($fakeIncomingOrderNotificationData['headers'])
            ->json('post', $fakeIncomingOrderNotificationData['uri'], $fakeIncomingOrderNotificationData['payload']);

        $response->assertStatus(401);
    }

    public function test_refund_signature_verification_success_when_data_not_modified()
    {
        $fakeIncomingRefundNotificationData = $this->fakeIncomingRefundNotificationData();
        $response = $this
            ->withHeaders($fakeIncomingRefundNotificationData['headers'])
            ->json('post', $fakeIncomingRefundNotificationData['uri'], $fakeIncomingRefundNotificationData['payload']);

        $response->assertStatus(200);
    }

    public function test_refund_signature_verification_fail_when_data_modified()
    {
        $fakeIncomingRefundNotificationData = $this->fakeIncomingRefundNotificationData();
        $fakeIncomingRefundNotificationData['payload']['refund']['status'] = 'COMPLETED';
        $response = $this
            ->withHeaders($fakeIncomingRefundNotificationData['headers'])
            ->json('post', $fakeIncomingRefundNotificationData['uri'], $fakeIncomingRefundNotificationData['payload']);

        $response->assertStatus(401);
    }

    public function test_transaction_not_found_404_return()
    {
        $fakeIncomingOrderNotificationData = $this->fakeIncomingOrderNotificationData();
        $response = $this
            ->withHeaders($fakeIncomingOrderNotificationData['headers'])
            ->json('post', $fakeIncomingOrderNotificationData['uri'] . 'fake', $fakeIncomingOrderNotificationData['payload']);

        $response->assertStatus(404);
    }

    public function test_order_transaction_status_updated_after_notification_received()
    {
        $transaction = $this->createFakeTransaction(PaymentStatus::INITIALIZED);

        $this->assertDatabaseHas('payu_transactions', [
            'id' => $transaction->id,
            'status' => PaymentStatus::INITIALIZED,
        ]);

        $fakeIncomingOrderNotificationData = $this->fakeIncomingOrderNotificationData($transaction, PaymentStatus::CANCELED);
        $this
            ->withHeaders($fakeIncomingOrderNotificationData['headers'])
            ->json('post', $fakeIncomingOrderNotificationData['uri'], $fakeIncomingOrderNotificationData['payload'])
            ->assertStatus(200);

        $this->assertDatabaseHas('payu_transactions', [
            'id' => $transaction->id,
            'status' => PaymentStatus::CANCELED,
        ]);
    }

    public function test_refund_status_updated_after_notification_received()
    {
        $transaction = $this->createFakeTransaction(PaymentStatus::INITIALIZED);

        $this->assertDatabaseHas('payu_transactions', [
            'id' => $transaction->id,
            'status' => PaymentStatus::INITIALIZED,
        ]);

        $fakeIncomingRefundNotificationData = $this->fakeIncomingRefundNotificationData($transaction, RefundStatus::ERROR);
        $this
            ->withHeaders($fakeIncomingRefundNotificationData['headers'])
            ->json('post', $fakeIncomingRefundNotificationData['uri'], $fakeIncomingRefundNotificationData['payload'])
            ->assertStatus(200);

        $this->assertDatabaseHas('payu_refunds', [
            'transaction_id' => $transaction->id,
            'status' => RefundStatus::ERROR,
        ]);
    }
}


