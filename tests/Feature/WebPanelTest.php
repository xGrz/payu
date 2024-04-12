<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebPanelTest extends TestCase
{

    use RefreshDatabase;

    public function test_web_panel_is_accessable()
    {
//        dd(Config::get('payu.routing.web'), Route::getRoutes());
//        $routeName = 'openpayu.payments.index';
//        $this
//            ->get(route($routeName))
//            ->assertStatus(200);
    }

}


