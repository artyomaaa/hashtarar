<?php

declare(strict_types=1);

namespace App\Repositories\Application;

use App\Models\Application;
use App\Models\ApplicationActivityLog;
use App\Models\Model;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository as ApplicationActivityLogRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ApplicationActivityLogRepository
    extends BaseRepository
    implements ApplicationActivityLogRepositoryContract
{
    public function __construct(ApplicationActivityLog $model)
    {
        parent::__construct($model);
    }

    public function getActivityLogsBy(int $applicationId): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->with(['application', 'user'])
            ->where('application_id', $applicationId)
            ->allowedFilters([
                AllowedFilter::exact('type'),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'created_at'
            )
            ->paginate(request()->query("per_page", 20));
    }

    public function log(int $applicationId, int $userId, string $type, array $data = null): ApplicationActivityLog
    {
        return $this->model->create([
            'application_id' => $applicationId,
            'user_id' => $userId,
            'type' => $type,
            'data' => $data,
        ]);
    }

    public function logWithOldData(int $applicationId, int $userId, string $type, array $data, Model $model): ApplicationActivityLog|null
    {
        $activityLog = null;
        $oldData = $model->getAttributes();
        $changedData = [];

        foreach ($data as $key => $value) {
            if ($oldData[$key] !== $value) {
                $changedData[$key] = [
                    'old' => $oldData[$key],
                    'new' => $value,
                ];
            }
        }

        if (count($changedData)) {
            $activityLog =  $this->model->create([
                'application_id' => $applicationId,
                'user_id' => $userId,
                'type' => $type,
                'data' => $changedData,
            ]);
        }

        return $activityLog;
    }

    public function getLatestRejectionReason(int $applicationId): ApplicationActivityLog|null
    {
        return $this->model->query()->latest()->first();
    }
}
