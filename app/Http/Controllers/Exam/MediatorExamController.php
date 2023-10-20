<?php
declare(strict_types=1);

namespace App\Http\Controllers\Exam;


use App\Http\Requests\Exam\SetExamResultRequest;
use App\Http\Requests\Exam\UpdatedExamResultRequest;
use App\Http\Resources\Mediator\ExamParticipantMediatorResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\Contracts\MediatorExam\IMediatorExamRepository;
use App\Services\MediatorExam\MediatorExamService;

class MediatorExamController
{
    public function __construct(
        private readonly MediatorExamService     $mediatorExamService,
        private readonly IMediatorExamRepository $mediatorExamRepository,
    )
    {

    }

    public function mediatorsAccessToExam(int $id): SuccessResource
    {
        $mediators = $this->mediatorExamService->getMediatorsAccessToExamByCourseId($id);

        return SuccessResource::make([
            'data' => ExamParticipantMediatorResource::collection($mediators),
            'message' => trans('message.exam-participant-mediators')
        ]);
    }

    public function setExamResult(SetExamResultRequest $request): SuccessResource
    {
        $this->mediatorExamService->setExamResult($request->validated());
        return SuccessResource::make([
            'message' => trans('message.set-exam-result')
        ]);
    }

    public function updateExamResult(UpdatedExamResultRequest $request): SuccessResource
    {

        $this->mediatorExamRepository->updatedExamResult($request->validated());
        return SuccessResource::make([
            'message' => trans('message.set-exam-result')
        ]);
    }

}
