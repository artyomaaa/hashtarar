<?php

namespace App\Services\MediatorExam;


use App\Repositories\Contracts\Course\ICourseLessonRepository;
use App\Repositories\Contracts\Course\ICourseRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\Mediator\IMediatorCourseLessonRepository;
use App\Repositories\MediatorExam\MediatorExamRepository;

class MediatorExamService
{
    public function __construct(
        private readonly ICourseLessonRepository         $courseLessonRepository,
        private readonly IMediatorCourseLessonRepository $mediatorCourseLessonRepository,
        private readonly ICourseRepository               $courseRepository,
        private readonly IUserRepository                 $userRepository,
        private readonly MediatorExamRepository          $mediatorExamRepository,
    )
    {

    }


    public function getMediatorsAccessToExamByCourseId(int $courseId): object
    {

        $lessonIds = $this->courseLessonRepository->getLessonIds($courseId);
        $mediatorIds = $this->mediatorCourseLessonRepository->getMediatorIdsByLessonIds($lessonIds);

        //TODO mediators value count
        $countMediatorIds = array_count_values($mediatorIds);

        $minHoursForExam = $this->courseRepository->getMinHoursForExamByCourseId($courseId);

        foreach ($countMediatorIds as $key => $value) {
            if ($value < $minHoursForExam) {
                unset($countMediatorIds[$key]);
            }
        }

        return $this->userRepository->getExamParticipantMediators(array_keys($countMediatorIds));
    }

    public function setExamResult(array $data)
    {
        $insertData = [];
        foreach ($data['examResult'] as $value) {
            $insertData[] = [
                'user_id' => $value['user_id'],
                'course_id' => $value['course_id'],
                'exam_result' => $value['exam_result'],
                'qualifications' => json_encode($data['qualifications']),
                'created_at' => now(),
                'updated_at' => now(),

            ];
        }
        return $this->mediatorExamRepository->insert($insertData);
    }

}
