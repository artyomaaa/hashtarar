<?php

declare(strict_types=1);

namespace App\Repositories\MediatorCompany;

use App\Models\MediatorCompany;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\MediatorCompany\IMediatorCompanyRepository;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class MediatorCompanyRepository extends BaseRepository implements IMediatorCompanyRepository
{

    public function __construct(MediatorCompany $model)
    {
        parent::__construct($model);
    }

    public function getMediatorCompanyNameById(int $companyId): string
    {
        return $this->model->where('id', $companyId)->value('company_name');

    }

    public function getMediatorCompanies(): Collection
    {
        return QueryBuilder::for($this->model)->allowedFilters([AllowedFilter::exact('status')])->get();
    }

}
