<?php

namespace xGrz\PayU\Enums;


enum RefundStatus: int
{
    case INITIALIZED = 0;
    case SENT = 1;
    case PENDING = 2;
    case CANCELED = 3;
    case FINALIZED = 4;
    case ERROR = 5;

}
