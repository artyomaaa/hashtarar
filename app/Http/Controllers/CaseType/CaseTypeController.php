<?php

namespace App\Http\Controllers\CaseType;

use App\Http\Requests\CaseType\DestroyCaseTypeRequest;
use App\Http\Requests\CaseType\GetCaseTypeRequest;
use App\Http\Requests\CaseType\GetCaseTypesRequest;
use App\Http\Requests\CaseType\StoreCaseTypeRequest;
use App\Http\Requests\CaseType\UpdateCaseTypeRequest;
use App\Http\Requests\CaseType\UpdateCaseTypeStatusRequest;
use App\Http\Resources\CaseType\CaseTypeResource;
use App\Http\Resources\SuccessResource;
use App\Models\CaseType;
use App\Repositories\Contracts\ICaseTypeRepository;

class CaseTypeController
{
    public function __construct(
        private ICaseTypeRepository $caseTypeRepository,
    )
    {

    }

    public function index(GetCaseTypesRequest $request): SuccessResource
    {
        return SuccessResource::make([
            'data' => CaseTypeResource::collection($this->caseTypeRepository->getCaseTypes()),
        ]);
    }

    public function store(StoreCaseTypeRequest $request): SuccessResource
    {
        $caseType = $this->caseTypeRepository->create($request->validated());

        return SuccessResource::make([
            'data' => CaseTypeResource::make($caseType),
            'message' => trans('message.case-type-created')
        ]);
    }

    public function show(GetCaseTypeRequest $request, CaseType $caseType): SuccessResource
    {
        return SuccessResource::make([
            'data' => CaseTypeResource::make($caseType),
        ]);
    }

    public function destroy(DestroyCaseTypeRequest $request, CaseType $caseType): SuccessResource
    {
        $this->caseTypeRepository->delete($caseType->id);

        return SuccessResource::make([
            'message' => trans('message.case-type-deleted')
        ]);
    }

    public function update(UpdateCaseTypeRequest $request, CaseType $caseType): SuccessResource
    {
        $this->caseTypeRepository->update($caseType->id, $request->validated());

        return SuccessResource::make([
            'message' => trans('message.case-type-updated')
        ]);
    }

    public function updateStatus(UpdateCaseTypeStatusRequest $request, int $id): SuccessResource
    {
        $data = $request->validated();
        $this->caseTypeRepository->update($id,['status'=> $data['status']] );
        return SuccessResource::make([
            'message' => trans('message.case-type-updated')
        ]);
    }
}
