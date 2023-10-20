<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationResultStatuses: string
{
    case RESOLVED = 'resolved';
    case UNRESOLVED = 'unresolved';
}

