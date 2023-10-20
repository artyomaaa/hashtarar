<?php

declare(strict_types=1);

namespace App\Enums;

enum MediatorAttendances: int
{
    case PRESENT = 1;
    case ABSENT = 0;
}

