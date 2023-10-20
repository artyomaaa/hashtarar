<?php

namespace App\Http\Controllers\Application;

use App\Http\Requests\Application\ApplicationComment\GetApplicationCommentRequest;
use App\Http\Requests\Application\ApplicationComment\StoreApplicationCommentRequest;
use App\Http\Resources\Application\ApplicationCommentResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Application;
use App\Repositories\Contracts\Application\IApplicationCommentRepository;
use App\Repositories\Contracts\Application\IApplicationRepository;

class ApplicationCommentController
{
    public function __construct(
        private IApplicationRepository        $applicationRepository,
        private IApplicationCommentRepository $applicationCommentRepository,
    )
    {

    }

    public function store(StoreApplicationCommentRequest $request, Application $application)
    {
        $data = $request->validated();
        $application = $this->applicationRepository->findById($application->id);

        if (!$application->mediator) {
            return ErrorResource::make([
                'message' => trans('message.mediator-not-selected')
            ]);
        }

        $data['commentator_id'] = auth()->id();
        $data['application_id'] = $application->id;
        $comment = $this->applicationCommentRepository->create($data);

        return SuccessResource::make([
            'data' => ApplicationCommentResource::make($comment),
            'message' => trans('message.application-comment-created')
        ]);
    }

    public function show(GetApplicationCommentRequest $request, int $applicationId): SuccessResource|ErrorResource
    {
        $applicationComment = $this->applicationCommentRepository->getApplicationCommentByApplicationID($applicationId);
        if (!$applicationComment) {
            return ErrorResource::make([
                'message' => trans('message.not-found')
            ]);
        }
        return SuccessResource::make([
            'data' => ApplicationCommentResource::collection($applicationComment)
        ]);


    }

}
