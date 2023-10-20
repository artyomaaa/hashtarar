<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\MediatorApplication;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

interface IMediatorApplicationRepository
{
    public function getMediatorApplicationsById(int $mediatorId): object;

    public function getMediatorApplications(int|null $mediatorApplicationId, int $userId);

    public function getAuthMediatorApplications(int $mediatorId);

}
