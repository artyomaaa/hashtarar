<?php

namespace App\Services\Application;

use App\Models\Application;
use App\Repositories\Contracts\Application\IApplicationAttachmentRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class JudgeMediatorApplicationAttachmentService
{
    public function __construct(
        private readonly IApplicationAttachmentRepository $applicationAttachmentRepository,
    )
    {

    }

    public function store(Application $application, object $document): void
    {
        $filename = (time() + rand(1, 10000)) . '_' . $document->getClientOriginalName();
        $path = $application->getDocumentsPath() . '/' . $filename;
        Storage::put($path, File::get($document));
        $this->applicationAttachmentRepository->create([
            'application_id' => $application->id,
            'name' => $filename,
            'path' => $path
        ]);

    }

}
