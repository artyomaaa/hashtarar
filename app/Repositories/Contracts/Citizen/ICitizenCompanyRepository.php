<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Citizen;

interface ICitizenCompanyRepository
{
    public function findById(int $userId);

    public function saveCompany(array $data);
}
