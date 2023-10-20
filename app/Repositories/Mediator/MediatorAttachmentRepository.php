<?php

declare(strict_types=1);

namespace App\Repositories\Mediator;

use App\Models\MediatorAttachment;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Mediator\IMediatorAttachmentRepository as MediatorAttachmentRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

final class MediatorAttachmentRepository
    extends BaseRepository
    implements MediatorAttachmentRepositoryContract
{
    public function __construct(MediatorAttachment $model)
    {
        parent::__construct($model);
    }

    public function findById(int $mediatorId, int $attachmentId): MediatorAttachment|null
    {
        return $this->model->where('id', $attachmentId)->where('mediator_id', $mediatorId)->firstOrFail();
    }

    public function getByIds(int $mediatorId, array $attachmentIds): Collection
    {
        return $this->model
            ->whereIn('id', $attachmentIds)
            ->where('mediator_id', $mediatorId)
            ->get();
    }
}
