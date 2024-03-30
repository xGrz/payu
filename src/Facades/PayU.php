<?php

namespace xGrz\PayU\Facades;

use xGrz\PayU\Facades\Traits\PayUBalance;
use xGrz\PayU\Facades\Traits\PayUMethods;
use xGrz\PayU\Facades\Traits\PayUPayouts;
use xGrz\PayU\Facades\Traits\PayURefunds;
use xGrz\PayU\Facades\Traits\PayUTransaction;

class PayU
{
    use PayUTransaction;
    use PayURefunds;
    use PayUPayouts;
    use PayUMethods;
    use PayUBalance;
}
