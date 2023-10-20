<?php

declare(strict_types=1);

namespace App\Http\Controllers\Mediator;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaseType\UpdateCaseTypeStatusRequest;
use App\Http\Requests\Mediator\MediatorCompany\DestroyMediatorCompanyRequest;
use App\Http\Requests\Mediator\MediatorCompany\GetMediatorCompanyRequest;
use App\Http\Requests\Mediator\MediatorCompany\StoreMediatorCompanyRequest;
use App\Http\Requests\Mediator\MediatorCompany\UpdatedMediatorCompanyRequest;
use App\Http\Resources\Mediator\MediatorCompanyResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\MediatorCompany\MediatorCompanyRepository;

final class MediatorCompaniesController extends Controller
{
    public function __construct
    (
        private readonly MediatorCompanyRepository $mediatorCompanyRepository,
    )
    {
    }

    public function index(GetMediatorCompanyRequest $request): SuccessResource
    {
        return SuccessResource::make([
            'data' => MediatorCompanyResource::collection($this->mediatorCompanyRepository->getMediatorCompanies()),
            'message' => trans('message.mediator-companies')
        ]);
    }

    public function store(StoreMediatorCompanyRequest $request): SuccessResource
    {
        $this->mediatorCompanyRepository->create($request->validated());
        return SuccessResource::make([
            'message' => trans('message.mediator-companies-created')
        ]);
    }

    public function update(UpdatedMediatorCompanyRequest $request, int $mediatorCompanyId): SuccessResource
    {
        $this->mediatorCompanyRepository->update($mediatorCompanyId, $request->validated());
        return SuccessResource::make([
            'message' => trans('message.mediator-companies-updated-successful')
        ]);
    }

    public function show(GetMediatorCompanyRequest $request, int $mediatorCompanyId): SuccessResource
    {
        return SuccessResource::make([
            'data' => MediatorCompanyResource::make($this->mediatorCompanyRepository->find($mediatorCompanyId)),
        ]);
    }

    public function destroy(DestroyMediatorCompanyRequest $request, int $mediatorCompanyId): SuccessResource
    {
        $this->mediatorCompanyRepository->delete($mediatorCompanyId);
        return SuccessResource::make([
            'message' => trans('message.mediator-companies-deleted-successful')
        ]);
    }

    public function updateStatus(UpdateCaseTypeStatusRequest $request, int $id): SuccessResource
    {
        $data = $request->validated();
        $this->mediatorCompanyRepository->update($id,['status'=> $data['status']] );
        return SuccessResource::make([
            'message' => trans('message.mediator-companies-updated-successful')
        ]);
    }
}
