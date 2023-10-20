<?php

declare(strict_types=1);

namespace App\Enums;

enum MediatorApplicationStatuses: string
{
    case REJECTED = 'rejected'; // Admin rejected application

    case PENDING = 'pending'; // Pending response from mediator +

    case FINISHED = 'finished'; // Mediator finished work
}

