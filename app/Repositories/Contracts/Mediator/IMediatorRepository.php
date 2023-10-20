<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Mediator;

interface IMediatorRepository
{
    public function getMediators();

    public function findById(int $mediatorId);

    public function findByUserId(int $userId);

    public function updateOrCreate(array $data);

    public function getMediatorDetailsGroupId(int $mediatorId);
}
