<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\MediatorCompany;

interface IMediatorCompanyRepository
{

    public function getMediatorCompanyNameById(int $companyId): string;

}
