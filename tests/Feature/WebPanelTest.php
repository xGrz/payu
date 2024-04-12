<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WebPanelTest extends TestCase
{

    use DatabaseMigrations;

    public function test_web_panel_is_accessable()
    {
        $this->get(route(\xGrz\PayU\Facades\Config::getRouteName('payments.index')))
            ->assertStatus(200);
        ;
    }

}


