<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationStatuses: string
{
    case NEW = 'new'; // Initial status of application
    case CONFIRMED = 'confirmed'; // Admin confirmed application
    case REJECTED = 'rejected'; // Admin rejected application
    case PENDING = 'pending'; // Pending response from mediator
    case IN_PROGRESS = 'in-progress'; // Mediator accepts and work in it
    case FINISHED = 'finished'; // Mediator finished work

    case PENDING_JUDGE = 'pending_judge'; // Pending response from judge

}

