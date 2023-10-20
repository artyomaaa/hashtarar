<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\MediatorExam;

interface IMediatorExamRepository
{

    public function updatedExamResult($data): bool;

}
