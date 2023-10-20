<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\User;

interface IUserRepository
{
    public function findByEmail(string $email): User|null;

    public function findById(int $id): User|null;

    public function findBySsn(string $ssn): User|null;

    public function firstOrCreate(array $data);

    public function getExamParticipantMediators(array $countMediatorIds);
}
