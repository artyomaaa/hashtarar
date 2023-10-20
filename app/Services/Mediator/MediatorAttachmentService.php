<?php

namespace App\Services\Mediator;

use App\Models\User;
use App\Repositories\Contracts\Mediator\IMediatorAttachmentRepository;
use App\Repositories\Contracts\Mediator\IMediatorRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MediatorAttachmentService
{
    public function __construct(
        private IMediatorAttachmentRepository $mediatorAttachmentRepository,
        private IMediatorRepository           $mediatorRepository
    )
    {

    }

    public function store(User $user, array $data): void
    {
        $attachments = $data['attachments'] ?? [];

        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                $filename = (time() + rand(1, 10000)) . '_' . $attachment->getClientOriginalName();
                $path = $user->getMediatorAttachmentsPath() . '/' . $filename;
                Storage::put($path, File::get($attachment));
                $this->mediatorAttachmentRepository->create([
                    'mediator_id' => $user->id,
                    'name' => $filename,
                    'path' => $path
                ]);
            }
        }

        $mediatorDetails = $this->mediatorRepository->findByUserId($user->id);

        if (count($data['deletedAttachmentsIds'] ?? [])) {
            $this->deleteByIds($mediatorDetails->user_id, $data['deletedAttachmentsIds']);
        }

        $updatedData = [];
        if (isset($data['avatar'])) {
            if ($mediatorDetails->avatar && Storage::disk('public')->exists($mediatorDetails->avatar)) {
                Storage::disk('public')->delete($mediatorDetails->avatar);
            }
            $updatedData['avatar'] = $this->storeAvatar($user, $data['avatar']);
        }

        if (isset($data['cv'])) {
            if ($mediatorDetails->cv && Storage::disk('public')->exists($mediatorDetails->cv)) {
                Storage::disk('public')->delete($mediatorDetails->cv);
            }
            $updatedData['cv'] = $this->storeCv($user, $data['cv']);
        }

        $this->mediatorRepository->update($mediatorDetails->id, $updatedData);
    }

    public function storeAvatar(User $user, $avatar): string
    {
        $filename = (time() + rand(1, 10000)) . '_' . $avatar->getClientOriginalName();
        $path = $user->getAvatarsPath() . '/' . $filename;
        Storage::disk('public')->put($path, File::get($avatar));

        return $path;
    }

    public function storeCv(User $user, $cv): string
    {
        $filename = (time() + rand(1, 10000)) . '_' . $cv->getClientOriginalName();
        $path = $user->getCvsPath() . '/' . $filename;
        Storage::disk('public')->put($path, File::get($cv));

        return $path;
    }

    public function deleteByIds(int $mediatorId, array $attachmentIds): void
    {
        $attachments = $this->mediatorAttachmentRepository->getByIds($mediatorId, $attachmentIds);

        foreach ($attachments as $attachment) {
            if (Storage::exists($attachment->path)) {
                Storage::delete($attachment->path);
            }

            $this->mediatorAttachmentRepository->delete($attachment->id);
        }
    }

    public function storeMediatorAvatar($user, $data): object
    {
        if ($user->mediatorDetails->avatar && Storage::disk('public')->exists($user->mediatorDetails->avatar)) {
            Storage::disk('public')->delete($user->mediatorDetails->avatar);
        }
        $updatedData['avatar'] = $this->storeAvatar($user, $data['avatar']);

        return $this->mediatorRepository->updateAndGetUpdatedData('user_id', $user->id, $updatedData);
    }
}
