<?php

declare(strict_types=1);

namespace App\Enums;

enum MediatorStatuses: string
{
    case NEW = 'new';
    case CANDIDATE = 'candidate';
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case TERMINATED = 'terminated';
}

