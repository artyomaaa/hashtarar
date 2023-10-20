<?php

namespace App\Http\Controllers\Application;

use App\Http\Requests\Application\ApplicationAttachment\DownloadApplicationAttachmentRequest;
use App\Http\Resources\ErrorResource;
use App\Models\Application;
use App\Models\ApplicationAttachment;
use App\Repositories\Contracts\Application\IApplicationAttachmentRepository as ApplicationAttachmentRepositoryContract;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationAttachmentController
{
    public function __construct(
        private ApplicationAttachmentRepositoryContract $applicationAttachmentRepository,
    )
    {

    }

    public function download(DownloadApplicationAttachmentRequest $request, Application $application, ApplicationAttachment $attachment): ErrorResource|StreamedResponse
    {
        $attachment = $this->applicationAttachmentRepository->findById($application->id, $attachment->id);

        if (Storage::exists($attachment->path)) {
            return Storage::download($attachment->path);
        }

        return ErrorResource::make([
            'message' => trans('messages.file-not-found')
        ]);
    }
}
