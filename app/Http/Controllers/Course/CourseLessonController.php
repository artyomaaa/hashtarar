<?php

namespace App\Http\Controllers\Course;

use App\Http\Requests\Course\CourseLesson\DestroyCourseLessonRequest;
use App\Http\Requests\Course\CourseLesson\GetCourseLessonMediatorsRequest;
use App\Http\Requests\Course\CourseLesson\GetCourseLessonRequest;
use App\Http\Requests\Course\CourseLesson\GetCourseLessonsRequest;
use App\Http\Requests\Course\CourseLesson\SetMediatorsAttendanceToLessonRequest;
use App\Http\Requests\Course\CourseLesson\StoreCourseLessonRequest;
use App\Http\Requests\Course\CourseLesson\UpdateCourseLessonRequest;
use App\Http\Resources\Course\CourseLessonResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\Mediator\MediatorCourseLessonResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Repositories\Contracts\Course\ICourseLessonRepository;
use App\Repositories\Contracts\Course\ICourseRepository;
use App\Repositories\Contracts\Mediator\IMediatorCourseLessonRepository;
use App\Repositories\Contracts\Mediator\IMediatorCourseRepository;
use Carbon\Carbon;

class CourseLessonController
{
    public function __construct(
        private readonly ICourseLessonRepository         $courseLessonRepository,
        private readonly IMediatorCourseLessonRepository $mediatorCourseLessonRepository,
        private readonly IMediatorCourseRepository       $mediatorCourseRepository,
        private readonly ICourseRepository               $courseRepository,
    )
    {

    }

    public function index(GetCourseLessonsRequest $request, Course $course): PaginationResource
    {
        $lessons = $this->courseLessonRepository->getLessons($course->id);

        return PaginationResource::make([
            'data' => CourseLessonResource::collection($lessons->items()),
            'pagination' => $lessons
        ]);
    }

    public function store(StoreCourseLessonRequest $request, Course $course): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $data['course_id'] = $course->id;

        $course = $this->courseRepository->find($data['course_id']);
        if (Carbon::parse($data['date'])->format('Y-m-d') < Carbon::parse($course->start_date)->format('Y-m-d')) {
            return ErrorResource::make([
                'message' => trans('messages.time-discrepancy')
            ]);
        }

        $lesson = $this->courseLessonRepository->create($data);

        return SuccessResource::make([
            'data' => CourseLessonResource::make($lesson),
            'message' => trans('message.lesson-created')
        ]);
    }

    public function show(GetCourseLessonRequest $request, Course $course, CourseLesson $courseLesson): SuccessResource
    {
        $lesson = $this->courseLessonRepository->findById($course->id, $courseLesson->id);

        return SuccessResource::make([
            'data' => CourseLessonResource::make($lesson),
        ]);
    }

    public function destroy(DestroyCourseLessonRequest $request, Course $course, CourseLesson $courseLesson): SuccessResource|ErrorResource
    {
        $lesson = $this->courseLessonRepository->findById($course->id, $courseLesson->id);

        if(Carbon::parse($lesson['date'])->format('Y-m-d') < Carbon::now()->format('Y-m-d')){
            return ErrorResource::make([
                'message' => trans('messages.you-can-not-delete-lesson')
            ]);
        }

        $this->courseLessonRepository->delete($lesson->id);

        return SuccessResource::make([
            'message' => trans('message.lesson-deleted')
        ]);
    }

    public function update(UpdateCourseLessonRequest $request, Course $course, CourseLesson $courseLesson): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $lesson = $this->courseLessonRepository->findById($course->id, $courseLesson->id);
        $course = $this->courseRepository->find($course->id);
        if (Carbon::parse($data['date'])->format('Y-m-d') < Carbon::parse($course->start_date)->format('Y-m-d')) {
            return ErrorResource::make([
                'message' => trans('messages.time-discrepancy')
            ]);
        }
        if(Carbon::parse($lesson['date'])->format('Y-m-d') < Carbon::now()->format('Y-m-d')){
            return ErrorResource::make([
                'message' => trans('messages.you-can-not-updated-lesson')
            ]);
        }
        $this->courseLessonRepository->update($lesson->id, $data);

        return SuccessResource::make([
            'message' => trans('message.lesson-updated')
        ]);
    }

    public function getMediators(GetCourseLessonMediatorsRequest $request, Course $course, CourseLesson $courseLesson): SuccessResource
    {
        $courseLessonMediators = $this->mediatorCourseLessonRepository->getByLessonId($course->id, $courseLesson->id);

        return SuccessResource::make([
            'data' => MediatorCourseLessonResource::collection($courseLessonMediators),
        ]);
    }

    public function setMediatorsAttendanceToLesson(SetMediatorsAttendanceToLessonRequest $request, int $courseId, int $courseLessonId): SuccessResource|ErrorResource
    {
        $validated = $request->validated();
        $courseLessonMediators = $this->mediatorCourseRepository->getRegisteredCourseMediatorIds($courseId);
        $commonMediators = array_intersect($courseLessonMediators, $validated['mediatorIds']);
        $this->mediatorCourseLessonRepository->mediatorsAttendanceToLesson($commonMediators, $courseLessonId);

        return SuccessResource::make([
            'message' => trans('message.mediators-attendance-to-lesson')
        ]);


    }
}
