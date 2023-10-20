<?php

namespace App\Services\Application;

use App\Models\ApplicationMeetingHistory;
use App\Repositories\Contracts\Application\IApplicationMeetingRecordingRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ApplicationMeetingHistoryAttachmentService
{
    public function __construct(
        private readonly IApplicationMeetingRecordingRepository $applicationMeetingRecordingRepository,
    )
    {

    }

    public function store(ApplicationMeetingHistory $meeting, array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $filename = (time() + rand(1, 10000)) . '_' . $attachment->getClientOriginalName();
            $path = $meeting->getAttachmentsPath() . '/' . $filename;
            Storage::put($path, File::get($attachment));
            $this->applicationMeetingRecordingRepository->create([
                'meeting_id' => $meeting->id,
                'name' => $filename,
                'path' => $path
            ]);
        }
    }

    public function deleteByIds(int $meetingId, array $attachmentIds): void
    {
        $attachments = $this->applicationMeetingRecordingRepository->getByIds($meetingId, $attachmentIds);

        foreach ($attachments as $attachment) {
            if (Storage::exists($attachment->path)) {
                Storage::delete($attachment->path);
            }

            $this->applicationMeetingRecordingRepository->delete($attachment->id);
        }
    }
}
