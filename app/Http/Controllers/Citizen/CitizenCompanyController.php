<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Requests\Citizen\CitizenCompany\StoreCitizenCompanyRequest;
use App\Http\Resources\Citizen\CitizenCompanyResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\Contracts\Citizen\ICitizenCompanyRepository;

class CitizenCompanyController
{
    public function __construct(
        private readonly ICitizenCompanyRepository $citizenCompanyRepository,
    )
    {

    }

    public function store(StoreCitizenCompanyRequest $request): SuccessResource
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $citizenCompany = $this->citizenCompanyRepository->saveCompany($data);

        return SuccessResource::make([
            'data' => CitizenCompanyResource::make($citizenCompany),
            'message' => trans('message.citizen-company-saved')
        ]);
    }
}
