<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Application;

interface IApplicationAttachmentRepository
{
    public function findById(int $applicationId, int $attachmentId);

    public function getByIds(int $applicationId, array $attachmentIds);
}
