<?php

namespace App\Http\Controllers\Mediator;

use App\Http\Requests\Mediator\MediatorAttachment\DownloadMediatorAttachmentRequest;
use App\Http\Resources\ErrorResource;
use App\Models\MediatorAttachment;
use App\Models\MediatorDetails;
use App\Repositories\Contracts\Mediator\IMediatorAttachmentRepository as MediatorAttachmentRepositoryContract;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediatorAttachmentController
{
    public function __construct(
        private MediatorAttachmentRepositoryContract $mediatorAttachmentRepository,
    )
    {

    }

    public function download(DownloadMediatorAttachmentRequest $request, MediatorDetails $mediatorDetails, MediatorAttachment $attachment): ErrorResource|StreamedResponse
    {
        $attachment = $this->mediatorAttachmentRepository->findById($mediatorDetails->user_id, $attachment->id);

        if (Storage::exists($attachment->path)) {
            return Storage::download($attachment->path);
        }

        return ErrorResource::make([
            'message' => trans('messages.file-not-found')
        ]);
    }
}
