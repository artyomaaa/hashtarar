<?php

namespace App\Services\Application;

use App\Models\MediatorApplication;
use App\Repositories\Contracts\Mediator\IApplicationMediatorSelectionRepository;
use App\Repositories\Contracts\Mediator\IMediatorAttachmentRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MediatorApplicationAttachmentService
{
    public function __construct(
        private readonly IMediatorAttachmentRepository           $mediatorAttachmentRepository,
        private readonly IApplicationMediatorSelectionRepository $applicationMediatorSelectionRepository,
    )
    {

    }

    public function store(MediatorApplication $mediatorApplication, array $data): void
    {
        $insertedData = [];
        if (!empty($data['attachments'])) {
            foreach ($data['attachments'] as $attachment) {
                $filename = (time() + rand(1, 10000)) . '_' . $attachment->getClientOriginalName();
                $path = $mediatorApplication->getAttachmentsPath() . '/' . $filename;
                Storage::put($path, File::get($attachment));
                $insertedData[] = [
                    'mediator_id' => $mediatorApplication->user_id,
                    'name' => $filename,
                    'path' => $path,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            $this->mediatorAttachmentRepository->insert($insertedData);
        }

        if (!empty($data['cv']) || !empty($data['avatar'])) {

            //TODO for Mediator CV
            $cvFileName = (time() + rand(1, 10000)) . '_' . $data['cv']->getClientOriginalName();
            $cvPath = $mediatorApplication->getCVPath() . '/' . $cvFileName;
            Storage::put($cvPath, File::get($data['cv']));

            //TODO for Mediator avatar
            $avatarFileName = (time() + rand(1, 10000)) . '_' . $data['avatar']->getClientOriginalName();
            $avatarPath = $mediatorApplication->getAvatarPath() . '/' . $avatarFileName;
            Storage::put($avatarPath, File::get($data['avatar']));

            $this->applicationMediatorSelectionRepository->updateMediatorDetails($data, $cvPath, $avatarPath);
        }
    }

}
