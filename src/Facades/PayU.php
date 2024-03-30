<?php

namespace xGrz\PayU\Facades;

use xGrz\PayU\Facades\Traits\PayUBalance;
use xGrz\PayU\Facades\Traits\PayUMethods;
use xGrz\PayU\Facades\Traits\PayUPayouts;
use xGrz\PayU\Facades\Traits\PayURefunds;

class PayU
{
    use PayUPayouts;
    use PayUTransaction;
    use PayURefunds;
    use PayUMethods;
    use PayUBalance;
}
