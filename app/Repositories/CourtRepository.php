<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Court;
use App\Repositories\Contracts\ICourtRepository as CourtRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

final class CourtRepository
    extends BaseRepository
    implements CourtRepositoryContract
{
    public function __construct(Court $model)
    {
        parent::__construct($model);
    }

    public function getCourts(): Collection
    {
        return $this->model->query()->get();
    }
}
