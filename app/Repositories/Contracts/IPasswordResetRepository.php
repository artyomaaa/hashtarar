<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\PasswordReset;

interface IPasswordResetRepository
{
    public function findBy(string $email, string $token): PasswordReset|null;
    public function findByEmail(string $email): PasswordReset|null;
    public function deleteByEmail(string $email);
}
