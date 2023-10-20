<?php

declare(strict_types=1);

namespace App\Repositories\Mediator;

use App\Enums\ApplicationStatuses;
use App\Enums\CaseTypeGroups;
use App\Enums\MediatorStatuses;
use App\Models\Application;
use App\Models\MediatorDetails;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Mediator\IApplicationMediatorSelectionRepository as ApplicationMediatorSelectionRepositoryContract;
use Illuminate\Support\Facades\DB;

final class ApplicationMediatorSelectionRepository
    extends BaseRepository
    implements ApplicationMediatorSelectionRepositoryContract
{
    public function __construct(MediatorDetails $model)
    {
        parent::__construct($model);
    }

    public function getMediatorCountsByGroupIds(Application $application): array
    {
        return $this->model->query()
            ->select(
                'group_id',
                DB::raw('COUNT(*) as count')
            )
            ->where('status', MediatorStatuses::ACTIVE->value)
            ->where(function ($query) use ($application) {
                if ($application->citizen_id) {
                    $query->whereNot('user_id', $application->citizen_id);
                }

                if ($application->judge_id) {
                    $query->whereNot('user_id', $application->judge_id);
                }
            })
            ->whereDoesntHave('rejections', function ($query) use ($application) {
                $query->where('application_id', $application->id);
            })
            ->groupBy('group_id')
            ->get()
            ->pluck('count', 'group_id')
            ->toArray();
    }

    public function getRandomMediator(Application $application): MediatorDetails|null
    {
        $mediatorCounts = $this->getMediatorCountsByGroupIds($application);
        $minMediatorSelectionPoolSize = config('app.min_mediator_selection_pool_size');

        $mediators = $this->model->query()
            ->select('mediator_details.*')
            ->withCount([
                "applications as in_progress_applications_count" => function ($query) {
                    $query->where('status', ApplicationStatuses::IN_PROGRESS->value);
                },
            ])
            ->where('status', MediatorStatuses::ACTIVE->value)
            ->where(function ($query) use ($application) {
                if ($application->citizen_id) {
                    $query->whereNot('user_id', $application->citizen_id);
                }

                if ($application->judge_id) {
                    $query->whereNot('user_id', $application->judge_id);
                }
            })
            ->whereDoesntHave('rejections', function ($query) use ($application) {
                $query->where('application_id', $application->id);
            })
            ->when(
                $application->caseType->group_id === CaseTypeGroups::LIST_1->value,
                function ($query) use ($mediatorCounts, $minMediatorSelectionPoolSize) {
                    return $query->when(
                        ($mediatorCounts[CaseTypeGroups::LIST_1->value] ?? 0) > $minMediatorSelectionPoolSize,
                        function ($query) {
                            return $query->where('group_id', CaseTypeGroups::LIST_1->value);
                        }
                    );
                },
                function ($query) use ($mediatorCounts, $minMediatorSelectionPoolSize) {
                    return $query->when(
                        ($mediatorCounts[CaseTypeGroups::LIST_2->value] ?? 0) > $minMediatorSelectionPoolSize,
                        function ($query) {
                            return $query->where('group_id', CaseTypeGroups::LIST_2->value);
                        }
                    );
                }
            )
            ->orderBy('in_progress_applications_count')
            ->get();

        $selectedMediator = $mediators->first(function ($mediator) use ($mediators) {
            return $mediator->in_progress_count === $mediators->min('in_progress_count');
        });

        if (!$selectedMediator) {
            $selectedMediator = $mediators->random();
        }

        return $selectedMediator;
    }

    public function updateMediatorDetails(array $data, string $cvPath, string $avatarPath): int
    {
        return $this->model->where(['user_id' => $data['user_id']])->update(
            [
                'had_license_before' => $data['had_license_before'],
                'cv' => $cvPath,
                'avatar' => $avatarPath,
            ]
        );
    }
}
