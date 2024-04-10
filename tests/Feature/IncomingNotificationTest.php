<?php

require_once(__DIR__ . '/../Traits/WithTransaction.php');

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Models\Transaction;
use xGrz\PayU\Services\ConfigService;

class IncomingNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function fakeIncomingOrderNotificationData(): array
    {
        $transaction = Transaction::create([
            'amount' => 1000,
            'status' => PaymentStatus::INITIALIZED,
            'payu_order_id' => '9GLFZSPNG9240409GUEST000P01',
            'payload' => [],
            'link' => 'https://google.com'
        ]);
        $payload = [
            'order' => [
                'orderId' => $transaction->payu_order_id,
                'extOrderId' => $transaction->id,
                'status' => PaymentStatus::PENDING->name,
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

    private function fakeIncomingRefundNotificationData(): array
    {
        $transaction = Transaction::create([
            'amount' => 1000,
            'status' => PaymentStatus::COMPLETED,
            'payu_order_id' => '9GLFZSPNG9240409GUEST000P01',
            'payload' => [],
            'link' => 'https://google.com'
        ]);
        $payload = [
            'orderId' => $transaction->payu_order_id,
            'extOrderId' => $transaction->id,
            'refund' => [
                'refundId' => 'STAOADHGADOJDAODA',
                'extRefundId' => $transaction->id,
                'amount' => 1000,
                'currencyCode' => 'PLN',
                'status' => RefundStatus::FINALIZED->name,
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

}


