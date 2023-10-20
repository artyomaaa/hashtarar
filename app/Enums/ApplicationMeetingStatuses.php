<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationMeetingStatuses: string
{
    case UNCONFIRMED = 'unconfirmed';
    case CONFIRMED = 'confirmed';
    case REJECTED = 'rejected';
}

