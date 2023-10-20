<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CaseType;
use App\Repositories\Contracts\ICaseTypeRepository as CaseTypeRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CaseTypeRepository
    extends BaseRepository
    implements CaseTypeRepositoryContract
{
    public function __construct(CaseType $model)
    {
        parent::__construct($model);
    }

    public function getCaseTypes(): Collection
    {
        return QueryBuilder::for($this->model)
            ->allowedFilters([
                AllowedFilter::exact('status')
            ])
            ->get();
    }
}
