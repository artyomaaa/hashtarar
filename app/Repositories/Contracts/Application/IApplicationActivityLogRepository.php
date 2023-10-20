<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Application;

use App\Models\Application;
use App\Models\Model;

interface IApplicationActivityLogRepository
{
    public function getActivityLogsBy(int $applicationId);

    public function log(int $applicationId, int $userId, string $type, array $data);

    public function logWithOldData(int $applicationId, int $userId, string $type, array $data, Model $model);

    public function getLatestRejectionReason(int $applicationId);
}
