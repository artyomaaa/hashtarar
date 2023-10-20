<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

interface IRoleRepository
{
    public function findByName(string $name);

    public function getRoles();

    public function getRoleNames(): array;
}
