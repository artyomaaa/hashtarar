<?php

namespace App\Services\Application;

use App\Models\Application;
use App\Repositories\Contracts\Application\IApplicationAttachmentRepository;
use App\Repositories\Contracts\Application\IApplicationRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ApplicationAttachmentService
{
    public function __construct(
        private readonly IApplicationAttachmentRepository $applicationAttachmentRepository,
        private readonly IApplicationRepository           $applicationRepository,
    )
    {

    }

    public function store(Application $application, array $attachments, object $document = null): array
    {
        $attachmentNames = [];

        foreach ($attachments as $attachment) {
            $filename = (time() + rand(1, 10000)) . '_' . $attachment->getClientOriginalName();
            $path = $application->getAttachmentsPath() . '/' . $filename;
            Storage::put($path, File::get($attachment));
            $this->applicationAttachmentRepository->create([
                'application_id' => $application->id,
                'name' => $filename,
                'path' => $path
            ]);
            $attachmentNames[] = $filename;
        }

        if (isset($document)) {
            $filename = (time() + rand(1, 10000)) . '_' . $document->getClientOriginalName();
            $path = $application->getDocumentsPath() . '/' . $filename;
            Storage::put($path, File::get($document));
            $this->applicationRepository->update($application->id, (array)$application + ['application' => $path]);
            $attachmentNames[] = $filename;
        }

        return $attachmentNames;
    }

    public function deleteByIds(int $applicationId, array $attachmentIds): array
    {
        $attachments = $this->applicationAttachmentRepository->getByIds($applicationId, $attachmentIds);
        $attachmentNames = [];
        foreach ($attachments as $attachment) {
            $attachmentNames[] = $attachment->name;
            if (Storage::exists($attachment->path)) {
                Storage::delete($attachment->path);
            }

            $this->applicationAttachmentRepository->delete($attachment->id);
        }

        return $attachmentNames;
    }
}
