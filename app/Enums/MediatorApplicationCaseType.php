<?php

declare(strict_types=1);

namespace App\Enums;

enum MediatorApplicationCaseType: string
{
    case BECOME_MEDIATOR = 'Դառնալ հաշտարար';
    case SUSPENSION_OF_QUALIFICATION = 'Որակավորման կասեցում';
    case TERMINATION_OF_QUALIFICATION = 'Որակավորման դադարեցում';
    case PARTICIPATION_IN_LIST_1 = 'Մասնակցություն ցանկ 1-ում';
    case PARTICIPATION_IN_LIST_2 = 'Մասնակցություն ցանկ 2-ում';
    case SUSPENSION_OF_PARTICIPATION_FROM_LIST_1 = 'Ցանկ 1-ից մասնակցության կասեցում';
    case SUSPENSION_OF_PARTICIPATION_FROM_LIST_2 = 'Ցանկ 2-ից մասնակցության կասեցում';
    case ACTIVATION_OF_QUALIFICATION = 'Որակավորման ակտիվացում';
}

