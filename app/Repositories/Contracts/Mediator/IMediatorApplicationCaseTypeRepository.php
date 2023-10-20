<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Mediator;

interface IMediatorApplicationCaseTypeRepository
{
    public function getCaseTypes(string $status);

    public function getCaseTypeIdByCaseTypeValue(string $caseTypeValue);

    public function getCaseTypeById(int $applicationTypeId);
}
