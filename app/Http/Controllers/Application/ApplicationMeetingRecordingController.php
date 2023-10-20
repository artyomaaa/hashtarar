<?php

namespace App\Http\Controllers\Application;

use App\Http\Requests\Application\ApplicationMeetingRecording\DownloadApplicationMeetingRecordingRequest;
use App\Http\Resources\ErrorResource;
use App\Models\Application;
use App\Models\ApplicationMeetingHistory;
use App\Models\ApplicationMeetingRecording;
use App\Repositories\Contracts\Application\IApplicationMeetingRecordingRepository;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationMeetingRecordingController
{
    public function __construct(
        private IApplicationMeetingRecordingRepository $applicationMeetingRecordingRepository,
    )
    {

    }

    public function download(DownloadApplicationMeetingRecordingRequest $request, Application $application, ApplicationMeetingHistory $meetingHistory, ApplicationMeetingRecording $recording): ErrorResource|StreamedResponse
    {
        $recording = $this->applicationMeetingRecordingRepository->findById($application->id, $meetingHistory->id, $recording->id,);

        if (Storage::exists($recording->path)) {
            return Storage::download($recording->path);
        }

        return ErrorResource::make([
            'message' => trans('messages.file-not-found')
        ]);
    }
}
