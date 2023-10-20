<?php

declare(strict_types=1);

namespace App\Services\MediatorApplication;


use App\Enums\CaseTypeGroups;
use App\Enums\MediatorApplicationCaseType;
use App\Enums\MediatorApplicationStatuses;
use App\Repositories\Contracts\Mediator\IMediatorApplicationCaseTypeRepository;
use App\Repositories\Contracts\Mediator\IMediatorRepository;
use App\Repositories\Contracts\MediatorApplication\IMediatorApplicationRepository;
use App\Services\Application\MediatorApplicationAttachmentService;


class MediatorApplicationService
{
    public function __construct(
        private readonly IMediatorApplicationRepository $mediatorApplicationRepository,
        private readonly IMediatorRepository            $mediatorRepository,
        private readonly IMediatorApplicationCaseTypeRepository   $mediatorApplicationCaseTypeRepository,

    )
    {

    }


    public function createMediatorApplication($data)
    {
        $insertedData = [
            'user_id' => auth()->id(),
            'status' => MediatorApplicationStatuses::PENDING->value,
            'application_type_id' => $data['application_type_id'],
            'application_cause' => $data['application_cause']
        ];
        return $this->mediatorApplicationRepository->create($insertedData);
    }

    public function mediatorGroupID(int $applicationTypeId, int $mediatorID): int|null
    {
        $groupId = null;

        $mediatorCaseType = $this->mediatorApplicationCaseTypeRepository->getCaseTypeById($applicationTypeId);
        $mediatorDetailsGroupId = $this->mediatorRepository->getMediatorDetailsGroupId($mediatorID);


        if ($mediatorDetailsGroupId == null && $mediatorCaseType === MediatorApplicationCaseType::PARTICIPATION_IN_LIST_1->value) {
            $groupId = CaseTypeGroups::LIST_1->value;
        }
        if ($mediatorDetailsGroupId == null && $mediatorCaseType === MediatorApplicationCaseType::PARTICIPATION_IN_LIST_2->value) {
            $groupId = CaseTypeGroups::LIST_2->value;
        }

        if($mediatorDetailsGroupId == CaseTypeGroups::LIST_1->value && $mediatorCaseType === MediatorApplicationCaseType::PARTICIPATION_IN_LIST_1->value){
            $groupId = CaseTypeGroups::LIST_1->value;
        }

        if($mediatorDetailsGroupId == CaseTypeGroups::LIST_1->value && $mediatorCaseType === MediatorApplicationCaseType::PARTICIPATION_IN_LIST_2->value){
            $groupId = CaseTypeGroups::LIST_3->value;
        }

        if($mediatorDetailsGroupId == CaseTypeGroups::LIST_2->value && $mediatorCaseType === MediatorApplicationCaseType::PARTICIPATION_IN_LIST_1->value){
            $groupId = CaseTypeGroups::LIST_3->value;
        }

        if($mediatorDetailsGroupId == CaseTypeGroups::LIST_2->value && $mediatorCaseType === MediatorApplicationCaseType::PARTICIPATION_IN_LIST_2->value){
            $groupId = CaseTypeGroups::LIST_2->value;
        }

        if($mediatorDetailsGroupId == CaseTypeGroups::LIST_3->value && $mediatorCaseType === MediatorApplicationCaseType::PARTICIPATION_IN_LIST_1->value){
            $groupId = CaseTypeGroups::LIST_1->value;
        }

        if($mediatorDetailsGroupId == CaseTypeGroups::LIST_3->value && $mediatorCaseType === MediatorApplicationCaseType::PARTICIPATION_IN_LIST_2->value){
            $groupId = CaseTypeGroups::LIST_2->value;
        }

        return $groupId;
    }

}
