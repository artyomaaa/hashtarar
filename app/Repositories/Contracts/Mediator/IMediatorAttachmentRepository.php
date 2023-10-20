<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Mediator;

interface IMediatorAttachmentRepository
{
    public function findById(int $mediatorId, int $attachmentId);

    public function getByIds(int $mediatorId, array $attachmentIds);
}
