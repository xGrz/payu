<?php

namespace xGrz\PayU\Enums;

enum PayoutStatus: int
{

    case INIT = 0;
    case PENDING = 1;
    case WAITING = 2;
    case CANCELED = 5;
    case REALIZED = 4;

}
