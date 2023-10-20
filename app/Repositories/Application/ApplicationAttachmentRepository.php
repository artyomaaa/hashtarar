<?php

declare(strict_types=1);

namespace App\Repositories\Application;

use App\Models\ApplicationAttachment;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Application\IApplicationAttachmentRepository as ApplicationAttachmentRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

final class ApplicationAttachmentRepository
    extends BaseRepository
    implements ApplicationAttachmentRepositoryContract
{
    public function __construct(ApplicationAttachment $model)
    {
        parent::__construct($model);
    }

    public function findById(int $applicationId, int $attachmentId): ApplicationAttachment|null
    {
        return $this->model->where('id', $attachmentId)->where('application_id', $applicationId)->firstOrFail();
    }

    public function getByIds(int $applicationId, array $attachmentIds): Collection
    {
        return $this->model
            ->whereIn('id', $attachmentIds)
            ->where('application_id', $applicationId)
            ->get();
    }
}
