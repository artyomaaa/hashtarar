<?php

namespace App\Services\Mediator;

use App\Enums\MediatorApplicationCaseType;
use App\Enums\MediatorStatuses;
use App\Repositories\Contracts\Mediator\IMediatorApplicationCaseTypeRepository;


class MediatorService
{
    public function __construct(
        private readonly IMediatorApplicationCaseTypeRepository $mediatorApplicationCaseTypeRepository
    )
    {

    }

    public function mediatorStatus(int $applicationTypeId): string
    {
        $status = '';

        $mediatorCaseType = $this->mediatorApplicationCaseTypeRepository->getCaseTypeById($applicationTypeId);

        if ($mediatorCaseType === MediatorApplicationCaseType::BECOME_MEDIATOR->value) {
            $status = MediatorStatuses::CANDIDATE->value;
        }

        if ($mediatorCaseType === MediatorApplicationCaseType::SUSPENSION_OF_QUALIFICATION->value) {
            $status = MediatorStatuses::SUSPENDED->value;
        }

        if ($mediatorCaseType === MediatorApplicationCaseType::TERMINATION_OF_QUALIFICATION->value) {
            $status = MediatorStatuses::TERMINATED->value;
        }

        if ($mediatorCaseType === MediatorApplicationCaseType::ACTIVATION_OF_QUALIFICATION->value) {
            $status = MediatorStatuses::ACTIVE->value;
        }

        if (in_array($mediatorCaseType, [
            MediatorApplicationCaseType::PARTICIPATION_IN_LIST_1->value,
            MediatorApplicationCaseType::PARTICIPATION_IN_LIST_2->value,
            MediatorApplicationCaseType::SUSPENSION_OF_PARTICIPATION_FROM_LIST_1->value,
            MediatorApplicationCaseType::SUSPENSION_OF_PARTICIPATION_FROM_LIST_2->value,
        ])) {
            $status = MediatorStatuses::ACTIVE->value;
        }

        return $status;

    }


}
