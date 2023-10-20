<?php

namespace App\Http\Controllers\Exam;


use App\Http\Requests\Exam\DestroyExamRequest;
use App\Http\Requests\Exam\GetExamsRequest;
use App\Http\Requests\Exam\StoreExamRequest;
use App\Http\Requests\Exam\UpdateExamRequest;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\Contracts\Course\ICourseRepository;
use App\Repositories\Contracts\Exam\IExamRepository;

class ExamController
{
    public function __construct(
        private readonly IExamRepository   $examRepository,
        private readonly ICourseRepository $courseRepository,
    )
    {

    }

    public function index(GetExamsRequest $request): PaginationResource
    {
        $exams = $this->examRepository->getExams();

        return PaginationResource::make([
            'data' => ExamResource::collection($exams->items()),
            'pagination' => $exams
        ]);
    }


    public function store(StoreExamRequest $request): SuccessResource
    {
        $result = $this->examRepository->create($request->validated());
        if ($result) {
            $this->courseRepository->update($result['course_id'], ['end_date' => $result['exam_date']]);
        }
        return SuccessResource::make([
            'message' => trans('message.exam-created')
        ]);
    }

    public function update(UpdateExamRequest $request, int $id): SuccessResource
    {
        $this->examRepository->update($id, $request->validated());

        return SuccessResource::make([
            'message' => trans('message.exam-updated')
        ]);
    }

    public function destroy(DestroyExamRequest $request, int $id): SuccessResource
    {
        $this->examRepository->delete($id);

        return SuccessResource::make([
            'message' => trans('message.exam-deleted')
        ]);
    }

}
