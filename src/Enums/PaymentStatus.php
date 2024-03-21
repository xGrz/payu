<?php

namespace xGrz\PayU\Enums;



enum PaymentStatus: int
{

    case INIT = 0;
    case NEW = 1;
    case PENDING = 2;
    case WAITING_FOR_CONFIRMATION = 3;
    case COMPLETED = 4;
    case CANCELED = 5;

}
