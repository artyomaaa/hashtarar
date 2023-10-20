<?php

declare(strict_types=1);

namespace App\Repositories\Citizen;

use App\Models\CitizenCompany;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Citizen\ICitizenCompanyRepository as CitizenCompanyRepositoryContract;


final class CitizenCompanyRepository
    extends BaseRepository
    implements CitizenCompanyRepositoryContract
{
    public function __construct(CitizenCompany $model)
    {
        parent::__construct($model);
    }

    public function findById(int $userId): CitizenCompany|null
    {
        return $this->model->where('user_id', $userId)->firstOrFail();
    }

    public function saveCompany(array $data)
    {
        return $this->model->updateOrCreate([
            'user_id' => $data['user_id']
        ], [
            'company_name' => $data['company_name'],
            'address' => $data['address'],
            'registration_number' => $data['registration_number'],
            'name_of_representative' => $data['name_of_representative'],
        ]);
    }
}
