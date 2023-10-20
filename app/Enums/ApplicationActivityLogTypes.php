<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationActivityLogTypes: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case ATTACHMENTS_REMOVED = 'attachments_removed';
    case ATTACHMENTS_ADDED = 'attachments_added';
    case REJECTED_BY_EMPLOYEE = 'rejected-by-employee';
    case CONFIRMED_BY_EMPLOYEE = 'confirmed-by-employee';
    case MEDIATOR_SELECTED = 'mediator-selected';
    case CONFIRMED_BY_MEDIATOR = 'confirmed-by-mediator';
    case REJECTED_BY_MEDIATOR = 'rejected-by-mediator';
    case FINISHED_BY_MEDIATOR = 'finished-by-mediator';
    case CONFIRMED_BY_JUDGE = 'confirmed-by-judge';
    case REJECTED_BY_JUDGE = 'rejected-by-judge';
    case MEDIATOR_CREATED_UPCOMING_MEETING = 'mediator-created-upcoming-meeting';
    case MEDIATOR_UPDATED_UPCOMING_MEETING = 'mediator-updated-upcoming-meeting';
    case MEDIATOR_REMOVED_UPCOMING_MEETING = 'mediator-removed-upcoming-meeting';
    case MEDIATOR_CONFIRMED_UPCOMING_MEETING = 'mediator-confirmed-upcoming-meeting';
    case MEDIATOR_REJECTED_UPCOMING_MEETING = 'mediator-rejected-upcoming-meeting';
    case REJECTED_UPCOMING_MEETING = 'rejected-upcoming-meeting';
    case CONFIRMED_UPCOMING_MEETING = 'confirmed-upcoming-meeting';
    case MEDIATOR_CREATED_MEETING_HISTORY = 'mediator-created-meeting-history';
    case MEDIATOR_UPDATED_MEETING_HISTORY = 'mediator-updated-meeting-history';
    case CITIZEN_ADDED = 'citizen_added';
}

