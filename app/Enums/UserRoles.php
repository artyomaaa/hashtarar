<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRoles: string
{
    case CITIZEN = 'citizen';
    case MEDIATOR = 'mediator';
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';
    case JUDGE = 'judge';


    public static function getRoleId(self $value): int
    {
        return match ($value) {
            UserRoles::CITIZEN => 1,
            UserRoles::MEDIATOR => 2,
            UserRoles::ADMIN => 3,
            UserRoles::EMPLOYEE => 4,
            UserRoles::JUDGE => 5,
        };
    }
}

