<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationMeetingTypes: string
{
    case ONLINE = 'online';
    case OFFLINE = 'offline';
}

