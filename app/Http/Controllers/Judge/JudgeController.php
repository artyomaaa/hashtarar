<?php

namespace App\Http\Controllers\Judge;

use App\Enums\UserRoles;
use App\Http\Requests\Judge\GetJudgeRequest;
use App\Http\Requests\Judge\GetJudgesRequest;
use App\Http\Requests\Judge\StoreJudgeRequest;
use App\Http\Requests\Judge\UpdateJudgeRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\Judge\JudgeResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserResource;
use App\Models\JudgeDetails;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\Judge\IJudgeRepository;

class JudgeController
{
    public function __construct(
        private IJudgeRepository $judgeRepository,
        private IUserRepository  $userRepository,
    )
    {

    }

    public function index(GetJudgesRequest $request): PaginationResource
    {
        $mediators = $this->judgeRepository->getJudges();

        return PaginationResource::make([
            'data' => JudgeResource::collection($mediators->items()),
            'pagination' => $mediators
        ]);
    }


    public function show(GetJudgeRequest $request, JudgeDetails $judgeDetails): SuccessResource
    {
        $judge = $this->judgeRepository->findById($judgeDetails->id);

        return SuccessResource::make([
            'data' => JudgeResource::make($judge)
        ]);
    }

    public function update(UpdateJudgeRequest $request, JudgeDetails $judgeDetails): SuccessResource|ErrorResource
    {
        $data = $request->validated();

        if ($judgeDetails?->user?->role?->name !== UserRoles::JUDGE->value) {
            return ErrorResource::make([
                'message' => trans('message.access-denied')
            ]);
        }

        $this->judgeRepository->update($judgeDetails->id, $data);

        return SuccessResource::make([
            'message' => trans('message.judge-updated')
        ]);
    }

    public function store(StoreJudgeRequest $request): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $user = $this->userRepository->find($data['user_id']);

        if ($user->role->name !== UserRoles::JUDGE->value) {
            return ErrorResource::make([
                'message' => trans('message.access-denied')
            ]);
        }

        $this->judgeRepository->updateOrCreate($data);

        return SuccessResource::make([
            'data' => UserResource::make($user),
            'message' => trans('message.judge-created')
        ]);
    }
}
