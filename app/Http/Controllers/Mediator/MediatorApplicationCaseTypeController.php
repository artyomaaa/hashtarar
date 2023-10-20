<?php

namespace App\Http\Controllers\Mediator;

use App\Http\Requests\Mediator\MediatorApplicationCaseType\DestroyMediatorApplicationCaseTypeRequest;
use App\Http\Requests\Mediator\MediatorApplicationCaseType\GetMediatorApplicationCaseTypeRequest;
use App\Http\Requests\Mediator\MediatorApplicationCaseType\GetMediatorApplicationCaseTypesRequest;
use App\Http\Requests\Mediator\MediatorApplicationCaseType\StoreMediatorApplicationCaseTypeRequest;
use App\Http\Requests\Mediator\MediatorApplicationCaseType\UpdateMediatorApplicationCaseTypeRequest;
use App\Http\Resources\Mediator\MediatorApplicationCaseTypeResource;
use App\Http\Resources\SuccessResource;
use App\Models\MediatorApplicationCaseType;
use App\Repositories\Contracts\Mediator\IMediatorApplicationCaseTypeRepository;

class MediatorApplicationCaseTypeController
{
    public function __construct(
        private IMediatorApplicationCaseTypeRepository $caseTypeRepository,
    )
    {

    }

    public function index(GetMediatorApplicationCaseTypesRequest $request): SuccessResource
    {
        $user = auth()->user();
        $status = $user->mediatorDetails?->status;
        $caseTypes = $this->caseTypeRepository->getCaseTypes($status);

        return SuccessResource::make([
            'data' => MediatorApplicationCaseTypeResource::collection($caseTypes),
        ]);
    }

    public function store(StoreMediatorApplicationCaseTypeRequest $request): SuccessResource
    {
        $caseType = $this->caseTypeRepository->create($request->validated());

        return SuccessResource::make([
            'data' => MediatorApplicationCaseTypeResource::make($caseType),
            'message' => trans('message.case-type-created')
        ]);
    }

    public function show(GetMediatorApplicationCaseTypeRequest $request, MediatorApplicationCaseType $caseType): SuccessResource
    {
        return SuccessResource::make([
            'data' => MediatorApplicationCaseTypeResource::make($caseType),
        ]);
    }

    public function destroy(DestroyMediatorApplicationCaseTypeRequest $request, MediatorApplicationCaseType $caseType): SuccessResource
    {
        $this->caseTypeRepository->delete($caseType->id);

        return SuccessResource::make([
            'message' => trans('message.case-type-deleted')
        ]);
    }

    public function update(UpdateMediatorApplicationCaseTypeRequest $request, MediatorApplicationCaseType $caseType): SuccessResource
    {
        $this->caseTypeRepository->update($caseType->id, $request->validated());

        return SuccessResource::make([
            'message' => trans('message.case-type-updated')
        ]);
    }
}
