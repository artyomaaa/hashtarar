<?php

declare(strict_types=1);

namespace App\Repositories\Mediator;

use App\Enums\MediatorStatuses;
use App\Enums\MediatorApplicationCaseType as ApplicationCaseType;
use App\Models\MediatorApplicationCaseType;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Mediator\IMediatorApplicationCaseTypeRepository as MediatorApplicationCaseTypeRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

final class MediatorApplicationCaseTypeRepository
    extends BaseRepository
    implements MediatorApplicationCaseTypeRepositoryContract
{
    public function __construct(MediatorApplicationCaseType $model)
    {
        parent::__construct($model);
    }

    public function getCaseTypes(string $status): Collection
    {

        $query = $this->model->query();
        if ($status === MediatorStatuses::NEW->value) {
            $query->where('name', ApplicationCaseType::BECOME_MEDIATOR->value);
        }
        return $query->get();
    }

    public function getCaseTypeIdByCaseTypeValue(string $caseTypeValue): int
    {
        return $this->model->where('name', $caseTypeValue)->select('id')->pluck('id')->first();
    }

    public function getCaseTypeById(int $applicationTypeId): string
    {
        return $this->model->where('id', $applicationTypeId)->value('name');
    }

}
