<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    public function __construct()
    {
        View::share('qbp_appName', 'xGrz/PayU');
        View::share('qbp_useTailwind', true);
        View::share('qbp_useAlpine', true);
        View::share('qbp_navigationTemplate', 'p::navigation.container');
        View::share('qbp_navigationItems', 'payu::layout.navigation-items');
        View::share('qbp_footerTemplate', 'payu::layout.footer-content');
    }
}
