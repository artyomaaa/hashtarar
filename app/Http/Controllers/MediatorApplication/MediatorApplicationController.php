<?php
declare(strict_types=1);

namespace App\Http\Controllers\MediatorApplication;


use App\Enums\CaseTypeGroups;
use App\Enums\MediatorApplicationCaseType;
use App\Enums\MediatorApplicationStatuses;
use App\Enums\MediatorStatuses;
use App\Enums\UserRoles;
use App\Http\Requests\ApplicationMediator\BecomeMediatorApplicationStatusRequest;
use App\Http\Requests\ApplicationMediator\DestroyMediatorApplicationRequest;
use App\Http\Requests\ApplicationMediator\GetAllMediatorApplicationsRequest;
use App\Http\Requests\ApplicationMediator\GetMediatorApplicationRequest;
use App\Http\Requests\ApplicationMediator\StoreMediatorApplicationRequest;
use App\Http\Requests\ApplicationMediator\UpdatedMediatorApplicationRequest;
use App\Http\Requests\ApplicationMediator\UpdatedMediatorApplicationStatusRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\MediatorApplication\GetMediatorApplicationResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\Contracts\IRoleRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\Mediator\IMediatorApplicationCaseTypeRepository;
use App\Repositories\Contracts\Mediator\IMediatorRepository;
use App\Repositories\Contracts\MediatorApplication\IMediatorApplicationRepository;
use App\Services\Application\MediatorApplicationAttachmentService;
use App\Services\Mediator\MediatorService;
use App\Services\MediatorApplication\MediatorApplicationService;
use Carbon\Carbon;

class MediatorApplicationController
{

    public function __construct(
        private readonly IMediatorApplicationRepository         $mediatorApplicationRepository,
        private readonly MediatorApplicationService             $mediatorApplicationService,
        private readonly MediatorService                        $mediatorService,
        private readonly IMediatorRepository                    $mediatorRepository,
        private readonly MediatorApplicationAttachmentService   $mediatorApplicationAttachmentService,
        private readonly IMediatorApplicationCaseTypeRepository $mediatorApplicationCaseTypeRepository,
        private readonly IUserRepository                        $userRepository,
        private readonly IRoleRepository                        $roleRepository,
    )
    {

    }

    public function index(GetMediatorApplicationRequest $request): PaginationResource
    {
        $mediatorApplications = $this->mediatorApplicationRepository->getMediatorApplications(null, auth()->user()->id);
        return PaginationResource::make([
            'data' => GetMediatorApplicationResource::collection($mediatorApplications->items()),
            'pagination' => $mediatorApplications
        ]);
    }


    public function store(StoreMediatorApplicationRequest $request): SuccessResource|ErrorResource
    {

        $mediatorApplications = $this->mediatorApplicationRepository->getAuthMediatorApplications(auth()->id());
        //TODO քանի որ առաջին պայմանով ստուգել եմ երկարության 1 լինելը ու խիստ հավասարություն է,դրա համար եմ երկրորդ պայմանով վերցնում 0-ը
        if (count($mediatorApplications) === 1 && $mediatorApplications[0]['status'] === MediatorApplicationStatuses::PENDING->value) {
            return ErrorResource::make([
                'message' => trans('messages.you-can-not-add-application')
            ]);
        }
        $result = $this->mediatorApplicationService->createMediatorApplication($request->validated());

        if ($result) {
            return SuccessResource::make([
                'message' => trans('message.application-created-successfully')
            ]);
        }

        return ErrorResource::make([
            'message' => trans('messages.something-went-wrong')
        ]);

    }

    public function update(UpdatedMediatorApplicationRequest $request, int $id): SuccessResource|ErrorResource
    {
        $isExistApplicationId = $this->mediatorApplicationRepository->find($id);

        if (!$isExistApplicationId) {
            return ErrorResource::make([
                'message' => trans('messages.something-went-wrong')
            ]);
        }
        $this->mediatorApplicationRepository->update($id, $request->validated());

        return SuccessResource::make([
            'message' => trans('message.mediator-application-updated')
        ]);
    }

    public function destroy(DestroyMediatorApplicationRequest $request, int $mediatorApplicationId): SuccessResource|ErrorResource
    {
        $isExistMediatorApplication = $this->mediatorApplicationRepository->find($mediatorApplicationId);
        if (!$isExistMediatorApplication) {
            return ErrorResource::make([
                'message' => trans('messages.something-went-wrong')
            ]);
        }

        if ($isExistMediatorApplication->status === MediatorApplicationStatuses::FINISHED->value) {
            return ErrorResource::make([
                'message' => trans('messages.you-can-not-delete-application')
            ]);
        }

        $this->mediatorApplicationRepository->delete($mediatorApplicationId);

        return SuccessResource::make([
            'message' => trans('message.mediator-application-deleted')
        ]);
    }


    public function getAllMediatorApplications(GetAllMediatorApplicationsRequest $request): PaginationResource
    {

        $mediatorApplications = $this->mediatorApplicationRepository->getMediatorApplications(null, auth()->user()->id);
        return PaginationResource::make([
            'data' => GetMediatorApplicationResource::collection($mediatorApplications->items()),
            'pagination' => $mediatorApplications
        ]);

    }

    public function updateStatus(UpdatedMediatorApplicationStatusRequest $request, int $applicationId): SuccessResource
    {
        $data = $request->validated();
        $updatedMediatorApplication = $this->mediatorApplicationRepository->updateAndGetUpdatedData('id', $applicationId, $data);

        if ($updatedMediatorApplication) {
            $status = $this->mediatorService->mediatorStatus($updatedMediatorApplication['application_type_id']);
            $this->mediatorRepository->updateAndGetUpdatedData('user_id', $updatedMediatorApplication->user_id, ['status' => $status]);
        }
        return SuccessResource::make([
            'data' => GetMediatorApplicationResource::make($updatedMediatorApplication),
            'message' => trans('message.mediator-application-updated')
        ]);

    }

    public function becomeMediator(BecomeMediatorApplicationStatusRequest $request): SuccessResource
    {
        $data = $request->validated();
        $data['status'] = MediatorApplicationStatuses::PENDING->value;
        $data['application_type_id'] = $this->mediatorApplicationCaseTypeRepository->getCaseTypeIdByCaseTypeValue(MediatorApplicationCaseType::BECOME_MEDIATOR->value);
        $mediatorApplication = $this->mediatorApplicationRepository->create(
            [
                'user_id' => $data['user_id'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'status' => $data['status'],
                'application_type_id' => $data['application_type_id']
            ]);
        if ($mediatorApplication) {
            $userRole = $this->roleRepository->findByName(UserRoles::MEDIATOR->value);
            $this->userRepository->update((int)$mediatorApplication['user_id'], ['role_id' => $userRole['id']]);
            $groupId = $this->mediatorApplicationService->mediatorGroupID((int)$mediatorApplication->application_type_id, (int)$mediatorApplication->user_id);
            $insertData = [
                'user_id' => $mediatorApplication->user_id,
                'group_id' => $groupId,
                'status' => MediatorStatuses::NEW->value,
            ];
            $this->mediatorRepository->updateOrCreate($insertData);
            $this->mediatorApplicationAttachmentService->store($mediatorApplication, $data);
        }
        return SuccessResource::make([
            'message' => trans('message.application-created')
        ]);
    }

    public function getMediatorApplication(GetMediatorApplicationRequest $request, int $id): SuccessResource
    {
        $mediatorApplication = $this->mediatorApplicationRepository->getMediatorApplications($id, auth()->user()->id);
        return SuccessResource::make([
            'data' => GetMediatorApplicationResource::make($mediatorApplication),
        ]);

    }

}
