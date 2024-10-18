<?php

use Tests\TestCase;
use xGrz\PayU\Facades\Config as Cfg;

class PackageConfigTest extends TestCase
{


    public function test_config_is_sandbox_mode()
    {
        $this->assertTrue(Cfg::isSandboxMode());
    }

    // payment
    public function test_get_payment_controller_class()
    {
        $this->assertEquals(
            \xGrz\PayU\Http\Controllers\PaymentController::class,
            Cfg::getPaymentController(),
        );
    }

    public function test_get_transaction_method_check_delay()
    {
        $this->assertEquals(
            60,
            Cfg::getTransactionMethodCheckDelay(),
        );
    }


    // refund
    public function test_get_refund_controller_class()
    {
        $this->assertEquals(
            \xGrz\PayU\Http\Controllers\RefundController::class,
            Cfg::getRefundController(),
        );
    }

    public function test_get_refund_send_delay()
    {
        $this->assertEquals(
            90,
            Cfg::getRefundSendDelay(),
        );
    }

    public function test_get_refund_retry_delay()
    {
        $this->assertEquals(
            60,
            Cfg::getRefundRetryDelay(),
        );
    }


    // payout
    public function test_get_payout_controller_class()
    {
        $this->assertEquals(
            \xGrz\PayU\Http\Controllers\PayoutController::class,
            Cfg::getPayoutController(),
        );
    }

    public function test_get_payout_send_delay()
    {
        $this->assertEquals(
            90,
            Cfg::getPayoutSendDelay(),
        );
    }

    public function test_get_payout_retry_delay()
    {
        $this->assertEquals(
            60,
            Cfg::getPayoutRetryDelay(),
        );
    }

    // methods
    public function test_get_methods_controller_class()
    {
        $this->assertEquals(
            \xGrz\PayU\Http\Controllers\MethodsController::class,
            Cfg::getMethodsController(),
        );
    }

    public function test_expose_admin_panel()
    {
        $this->assertEquals(
            true,
            config('payu.routing.expose_web_panel'),
            'Does your .env.testing has PAYU_EXPOSE_ADMIN_PANEL set to true?'
        );
    }






}


