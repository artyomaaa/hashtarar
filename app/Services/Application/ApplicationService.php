<?php

namespace App\Services\Application;

use App\Models\Application;
use App\Repositories\Contracts\Application\IApplicationRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ApplicationService
{
    public function __construct(
        private readonly IApplicationRepository $applicationRepository,
    )
    {

    }

    public function store(Application $application, object $attachment): string
    {
        $filename = (time() + rand(1, 10000)) . '_' . $attachment->getClientOriginalName();
        $path = $application->getApplicationPath() . '/' . $filename;
        Storage::put($path, File::get($attachment));
        $this->applicationRepository->update($application->id, ['application' => $path]);

        return $filename;
    }


    public function deleteApplicationFromStorage(string $path): void
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

}
