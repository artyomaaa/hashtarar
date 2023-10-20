<?php

namespace App\Http\Controllers\Mediator;

use App\Enums\ApplicationStatuses;
use App\Enums\MediatorApplicationCaseType;
use App\Enums\MediatorApplicationStatuses;
use App\Enums\MediatorStatuses;
use App\Enums\UserRoles;
use App\Http\Requests\Mediator\DownloadMediatorCvRequest;
use App\Http\Requests\Mediator\GetMediatorRequest;
use App\Http\Requests\Mediator\GetMediatorsRequest;
use App\Http\Requests\Mediator\StoreMediatorRequest;
use App\Http\Requests\Mediator\UpdateMediatorAttachmentsRequest;
use App\Http\Requests\Mediator\UpdateMediatorCVRequest;
use App\Http\Requests\Mediator\UpdateMediatorGroupRequest;
use App\Http\Requests\Mediator\UpdateMediatorInstitutionsRequest;
use App\Http\Requests\Mediator\UpdateMediatorRequest;
use App\Http\Requests\Mediator\UpdateMediatorSpecializationRequest;
use App\Http\Requests\Mediator\UpdateMediatorStatusRequest;
use App\Http\Requests\Mediator\UploadMediatorAvatarRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\Mediator\MediatorResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserResource;
use App\Models\MediatorDetails;
use App\Repositories\Contracts\IRoleRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\Mediator\IMediatorApplicationCaseTypeRepository;
use App\Repositories\Contracts\Mediator\IMediatorRepository;
use App\Repositories\Contracts\MediatorApplication\IMediatorApplicationRepository;
use App\Services\Mediator\MediatorAttachmentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediatorController
{
    public function __construct(
        private readonly IMediatorRepository                    $mediatorRepository,
        private readonly IUserRepository                        $userRepository,
        private readonly IRoleRepository                        $roleRepository,
        private readonly MediatorAttachmentService              $mediatorAttachmentService,
        private readonly IMediatorApplicationCaseTypeRepository $mediatorApplicationCaseTypeRepository,
        private readonly IMediatorApplicationRepository         $mediatorApplicationRepository,
    )
    {

    }

    public function index(GetMediatorsRequest $request): PaginationResource
    {
        $mediators = $this->mediatorRepository->getMediators();

        return PaginationResource::make([
            'data' => MediatorResource::collection($mediators->items()),
            'pagination' => $mediators
        ]);
    }

    public function downloadCv(DownloadMediatorCvRequest $request, MediatorDetails $mediatorDetails): ErrorResource|StreamedResponse
    {
        $mediator = $this->mediatorRepository->findOrFail($mediatorDetails->id);

        if ($mediator->cv && Storage::disk('public')->exists($mediator->cv)) {
            return Storage::disk('public')->download($mediator->cv);
        }

        return ErrorResource::make([
            'message' => trans('messages.file-not-found')
        ]);
    }

    public function updateStatus(UpdateMediatorStatusRequest $request, MediatorDetails $mediatorDetails): SuccessResource
    {
        $mediator = $this->mediatorRepository->findOrFail($mediatorDetails->id);

        $this->mediatorRepository->update($mediator->id, $request->validated());

        // TODO Send notification to mediator when his status is updated

        return SuccessResource::make([
            'message' => trans('message.mediator-status-updated')
        ]);
    }

    public function show(GetMediatorRequest $request, int $id): SuccessResource
    {
        $mediator = $this->mediatorRepository->findById($id);

        return SuccessResource::make([
            'data' => MediatorResource::make($mediator)
        ]);
    }

    public function updateGroup(UpdateMediatorGroupRequest $request, MediatorDetails $mediatorDetails): SuccessResource
    {
        $mediator = $this->mediatorRepository->findOrFail($mediatorDetails->id);

        $this->mediatorRepository->update($mediator->id, $request->validated());

        // TODO Send notification to mediator when his status is updated

        return SuccessResource::make([
            'message' => trans('message.mediator-group-updated')
        ]);
    }

    public function store(StoreMediatorRequest $request): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $data['status'] = MediatorStatuses::NEW->value;
        $user = $this->userRepository->find($data['user_id']);

        if (!in_array($user->role->name, [UserRoles::CITIZEN->value, UserRoles::MEDIATOR->value])) {
            return ErrorResource::make([
                'message' => trans('message.access-denied')
            ]);
        }

        if ($user->role->name === UserRoles::CITIZEN->value) {
            $roleId = $this->roleRepository->findByName(UserRoles::MEDIATOR->value)?->id;

            $this->userRepository->update($user->id, [
                'role_id' => $roleId
            ]);
        }

        //TODO create application
        $data['application_type_id'] = $this->mediatorApplicationCaseTypeRepository->getCaseTypeIdByCaseTypeValue(MediatorApplicationCaseType::BECOME_MEDIATOR->value);
        $this->mediatorApplicationRepository->create(
            [
                'user_id' => $data['user_id'],
                'created_at' => now(),
                'status' => MediatorApplicationStatuses::PENDING->value,
                'application_type_id' => $this->mediatorApplicationCaseTypeRepository->getCaseTypeIdByCaseTypeValue(MediatorApplicationCaseType::BECOME_MEDIATOR->value)
            ]
        );

        $data['mediator_id'] = $user->id;
        $this->mediatorRepository->updateOrCreate($data);
        $this->mediatorAttachmentService->store($user, $data);

        return SuccessResource::make([
            'data' => UserResource::make($user),
            'message' => trans('message.mediator-created')
        ]);
    }

    public function update(UpdateMediatorRequest $request, MediatorDetails $mediatorDetails): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $user = $mediatorDetails->user;

        if ($user?->role?->name !== UserRoles::MEDIATOR->value) {
            return ErrorResource::make([
                'message' => trans('message.access-denied'),
            ]);
        }

        $this->mediatorRepository->update($mediatorDetails->id, Arr::except($data, ['cv', 'avatar']));
        $this->mediatorAttachmentService->store($user, $data);

        return SuccessResource::make([
            'message' => trans('message.mediator-updated')
        ]);
    }

    public function updatedMediatorCv(UpdateMediatorCVRequest $request): SuccessResource
    {
        $data = $request->validated();
        $user = $this->userRepository->findById($data['id']);
        $this->mediatorAttachmentService->store($user, $data);

        return SuccessResource::make([
            'data' => $this->mediatorRepository->firstOrFail('user_id', $data['id']),
            'message' => trans('message.updated-successfully'),
        ]);
    }

    public function updatedMediatorSpecialization(UpdateMediatorSpecializationRequest $request): SuccessResource
    {
        $data = $request->validated();
        return SuccessResource::make([
            'data' => $this->mediatorRepository->updateAndGetUpdatedData('user_id', $data['id'], ['mediator_specialization' => $data['specialization']]),
            'message' => trans('message.updated-successfully'),
        ]);
    }

    public function updatedMediatorInstitutions(UpdateMediatorInstitutionsRequest $request): SuccessResource
    {
        $data = $request->validated();
        return SuccessResource::make([
            'data' => $this->mediatorRepository->updateAndGetUpdatedData('user_id', $data['id'], ['mediator_company_id' => $data['mediator_company_id']]),
            'message' => trans('message.updated-successfully'),
        ]);
    }

    public function uploadMediatorAvatar(UploadMediatorAvatarRequest $request): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $user = $this->userRepository->findById($data['id']);

        if ($user) {
            $data = $this->mediatorAttachmentService->storeMediatorAvatar($user, $data);

            return SuccessResource::make([
                'data' => public_path('storage/' . $data['avatar']),
                'message' => trans('message.updated-successfully'),
            ]);
        }

        return ErrorResource::make([
            'message' => trans('message.not-found'),
        ]);
    }

    public function changeMediatorAttachedDocuments(UpdateMediatorAttachmentsRequest $request): SuccessResource
    {
        $data = $request->validated();
        $user = $this->userRepository->findById($data['id']);

        $this->mediatorAttachmentService->store($user, $data);
        return SuccessResource::make([
            'data' => $this->mediatorRepository->firstOrFail('user_id', $data['id']),
            'message' => trans('message.updated-successfully'),
        ]);

    }
}
