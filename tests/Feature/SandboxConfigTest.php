<?php


use Tests\TestCase;
use xGrz\PayU\Facades\Config as PayUConfigFacade;

class SandboxConfigTest extends TestCase
{

    // use RefreshDatabase;

    public function test_config_in_sandbox_mode(): void
    {
        $this->assertTrue(config('payu.api.use_sandbox'));
    }

    public function test_config_facade_returns_sandbox_mode()
    {
        $this->assertTrue(PayUConfigFacade::isSandboxMode());
        if(!PayUConfigFacade::isSandboxMode()) die('Config not in sandbox mode');
    }


}
