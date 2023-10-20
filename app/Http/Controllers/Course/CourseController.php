<?php

namespace App\Http\Controllers\Course;

use App\Enums\MediatorStatuses;
use App\Enums\UserRoles;
use App\Http\Requests\Course\CourseMediator\SetMediatorsToCourseRequest;
use App\Http\Requests\Course\DestroyCourseRequest;
use App\Http\Requests\Course\GetCourseMediatorsRequest;
use App\Http\Requests\Course\GetCourseRequest;
use App\Http\Requests\Course\GetCoursesRequest;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\Mediator\MediatorCourseResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Models\Course;
use App\Repositories\Contracts\Course\ICourseLessonRepository;
use App\Repositories\Contracts\Course\ICourseRepository;
use App\Repositories\Contracts\Mediator\IMediatorCourseRepository;
use App\Repositories\Contracts\Mediator\IMediatorRepository;
use Carbon\Carbon;

class CourseController
{
    public function __construct(
        private readonly ICourseRepository         $courseRepository,
        private readonly IMediatorCourseRepository $mediatorCourseRepository,
        private readonly IMediatorRepository       $mediatorRepository,
        private readonly ICourseLessonRepository   $courseLessonRepository,
    )
    {

    }

    public function index(GetCoursesRequest $request): PaginationResource|SuccessResource
    {

        if (auth()->user()->role_id === UserRoles::getRoleId(UserRoles::MEDIATOR)) {
            $mediatorCourses = $this->courseRepository->getMediatorCourses(auth()->id());
            if (!empty($mediatorCourses->items())) {
                return SuccessResource::make([
                    'data' => CourseResource::collection($mediatorCourses->items()),
                    'message' => trans('message.course-created')
                ]);
            }
            $courses = $this->courseRepository->getCourses();
            return PaginationResource::make([
                'data' => CourseResource::collection($courses->items()),
                'pagination' => $courses
            ]);
        }

        $courses = $this->courseRepository->getCourses();
        return PaginationResource::make([
            'data' => CourseResource::collection($courses->items()),
            'pagination' => $courses
        ]);
    }

    public function store(StoreCourseRequest $request): SuccessResource
    {
        $court = $this->courseRepository->create($request->validated());

        return SuccessResource::make([
            'data' => CourseResource::make($court),
            'message' => trans('message.course-created')
        ]);
    }

    public function show(GetCourseRequest $request, Course $course): SuccessResource
    {
        return SuccessResource::make([
            'data' => CourseResource::make($course),
        ]);
    }

    public function destroy(DestroyCourseRequest $request, Course $course): SuccessResource
    {
        $this->courseRepository->delete($course->id);

        return SuccessResource::make([
            'message' => trans('message.course-deleted')
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course): SuccessResource|ErrorResource
    {
        $lessons = $this->courseLessonRepository->getLessons($course->id);
        if ($lessons->count() != 0) {
            return ErrorResource::make([
                'message' => trans('message.you-cannot-change-the-course-day-because-there-is-already-a-lessons-attached')
            ]);
        }


        $this->courseRepository->update($course->id, $request->validated());

        return SuccessResource::make([
            'message' => trans('message.course-updated')
        ]);
    }

    public function getMediators(GetCourseMediatorsRequest $request, Course $course): SuccessResource
    {
        $courseMediators = $this->mediatorCourseRepository->getByCourseId($course->id);

        return SuccessResource::make([
            'data' => MediatorCourseResource::collection($courseMediators),
        ]);
    }

    public function setCourseMediators(SetMediatorsToCourseRequest $request, int $courseId): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $isExistCourse = $this->courseRepository->find($courseId);
        $mediatorId = (int)$data['mediator_id'];
        $mediator = $this->mediatorRepository->findById($mediatorId);

        if ($mediator->status !== MediatorStatuses::CANDIDATE->value) {
            return ErrorResource::make([
                'message' => trans('message.you-cannot-attend-the-course')
            ]);

        }


        if ($isExistCourse) {
            $checkMediatorExistInCourse = $this->mediatorCourseRepository->checkMediatorExistInCourse($mediatorId, $courseId);
            if ($checkMediatorExistInCourse) {
                return ErrorResource::make([
                    'message' => trans('message.you-are-already-enrolled-in-the-course')
                ]);
            }
            $insertedData = [
                'mediator_id' => $mediatorId,
                'course_id' => $courseId,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
            $this->mediatorCourseRepository->create($insertedData);
        }
        return SuccessResource::make([
            'message' => trans('message.the-mediator-successfully-registered-for-the-course')
        ]);

    }
}
