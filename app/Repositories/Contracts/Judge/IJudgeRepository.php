<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Judge;

interface IJudgeRepository
{
    public function getJudges();

    public function findById(int $judgeId);

    public function findByUserId(int $userId);

    public function updateOrCreate(array $data);
}
