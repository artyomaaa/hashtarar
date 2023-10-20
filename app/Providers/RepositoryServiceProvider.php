<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Application\ApplicationActivityLogRepository;
use App\Repositories\Application\ApplicationAttachmentRepository;
use App\Repositories\Application\ApplicationCommentRepository;
use App\Repositories\Application\ApplicationMeetingHistoryRepository;
use App\Repositories\Application\ApplicationMeetingRecordingRepository;
use App\Repositories\Application\ApplicationRepository;
use App\Repositories\Application\ApplicationUpcomingMeetingRepository;
use App\Repositories\ApplicationMediator\ApplicationMediatorRejectionRepository;
use App\Repositories\ApplicationMediator\ApplicationMediatorResultRepository;
use App\Repositories\CaseTypeRepository;
use App\Repositories\Citizen\CitizenCompanyRepository;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository as ApplicationActivityLogRepositoryContract;
use App\Repositories\Contracts\Application\IApplicationAttachmentRepository as ApplicationAttachmentRepositoryContract;
use App\Repositories\Contracts\Application\IApplicationCommentRepository as ApplicationCommentRepositoryContract;
use App\Repositories\Contracts\Application\IApplicationMeetingHistoryRepository as ApplicationMeetingHistoryRepositoryContract;
use App\Repositories\Contracts\Application\IApplicationMeetingRecordingRepository as ApplicationMeetingRecordingRepositoryContract;
use App\Repositories\Contracts\Application\IApplicationRepository as ApplicationRepositoryContract;
use App\Repositories\Contracts\Application\IApplicationUpcomingMeetingRepository as ApplicationUpcomingMeetingRepositoryContract;
use App\Repositories\Contracts\ApplicationMediator\IApplicationMediatorRejectionRepository as ApplicationMediatorRejectionRepositoryContract;
use App\Repositories\Contracts\ApplicationMediator\IApplicationMediatorResultRepository as ApplicationMediatorResultRepositoryContract;
use App\Repositories\Contracts\Citizen\ICitizenCompanyRepository;
use App\Repositories\Contracts\Course\ICourseRepository as CourseRepositoryContract;
use App\Repositories\Contracts\Course\ICourseLessonRepository as CourseLessonRepositoryContract;
use App\Repositories\Contracts\Exam\IExamRepository;
use App\Repositories\Contracts\ICaseTypeRepository as CaseTypeRepositoryContract;
use App\Repositories\Contracts\ICourtRepository as CourtRepositoryContract;
use App\Repositories\Contracts\IPasswordResetRepository as PasswordResetRepositoryContract;
use App\Repositories\Contracts\IRoleRepository as RoleRepositoryContract;
use App\Repositories\Contracts\IQualificationsRepository;
use App\Repositories\Contracts\IUserRepository as UserRepositoryContract;
use App\Repositories\Contracts\Judge\IJudgeRepository as JudgeRepositoryContract;
use App\Repositories\Contracts\Mediator\IApplicationMediatorSelectionRepository as ApplicationMediatorSelectionRepositoryContract;
use App\Repositories\Contracts\Mediator\IMediatorApplicationCaseTypeRepository as MediatorApplicationCaseTypeRepositoryContract;
use App\Repositories\Contracts\Mediator\IMediatorAttachmentRepository as MediatorAttachmentRepositoryContract;
use App\Repositories\Contracts\Mediator\IMediatorCourseRepository as MediatorCourseRepositoryContract;
use App\Repositories\Contracts\Mediator\IMediatorCourseLessonRepository as MediatorCourseLessonRepositoryContract;
use App\Repositories\Contracts\Mediator\IMediatorRepository as MediatorRepositoryContract;
use App\Repositories\Contracts\MediatorApplication\IMediatorApplicationRepository;
use App\Repositories\Contracts\MediatorCompany\IMediatorCompanyRepository;
use App\Repositories\Contracts\MediatorExam\IMediatorExamRepository;
use App\Repositories\Course\CourseLessonRepository;
use App\Repositories\Course\CourseRepository;
use App\Repositories\CourtRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Judge\JudgeRepository;
use App\Repositories\Mediator\ApplicationMediatorSelectionRepository;
use App\Repositories\Mediator\MediatorApplicationCaseTypeRepository;
use App\Repositories\Mediator\MediatorAttachmentRepository;
use App\Repositories\Mediator\MediatorCourseLessonRepository;
use App\Repositories\Mediator\MediatorCourseRepository;
use App\Repositories\Mediator\MediatorRepository;
use App\Repositories\MediatorApplication\MediatorApplicationRepository;
use App\Repositories\MediatorCompany\MediatorCompanyRepository;
use App\Repositories\MediatorExam\MediatorExamRepository;
use App\Repositories\PasswordResetRepository;
use App\Repositories\RoleRepository;
use App\Repositories\QualificationsRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(RoleRepositoryContract::class, RoleRepository::class);
        $this->app->bind(CaseTypeRepositoryContract::class, CaseTypeRepository::class);
        $this->app->bind(CourtRepositoryContract::class, CourtRepository::class);
        $this->app->bind(CourseRepositoryContract::class, CourseRepository::class);
        $this->app->bind(CourseLessonRepositoryContract::class, CourseLessonRepository::class);
        $this->app->bind(PasswordResetRepositoryContract::class, PasswordResetRepository::class);

        $this->app->bind(ApplicationUpcomingMeetingRepositoryContract::class, ApplicationUpcomingMeetingRepository::class);
        $this->app->bind(ApplicationRepositoryContract::class, ApplicationRepository::class);
        $this->app->bind(ApplicationAttachmentRepositoryContract::class, ApplicationAttachmentRepository::class);
        $this->app->bind(ApplicationMeetingHistoryRepositoryContract::class, ApplicationMeetingHistoryRepository::class);
        $this->app->bind(ApplicationMeetingRecordingRepositoryContract::class, ApplicationMeetingRecordingRepository::class);
        $this->app->bind(ApplicationActivityLogRepositoryContract::class, ApplicationActivityLogRepository::class);
        $this->app->bind(ApplicationCommentRepositoryContract::class, ApplicationCommentRepository::class);

        $this->app->bind(MediatorRepositoryContract::class, MediatorRepository::class);
        $this->app->bind(MediatorAttachmentRepositoryContract::class, MediatorAttachmentRepository::class);
        $this->app->bind(MediatorCourseRepositoryContract::class, MediatorCourseRepository::class);
        $this->app->bind(MediatorCourseLessonRepositoryContract::class, MediatorCourseLessonRepository::class);
        $this->app->bind(ApplicationMediatorSelectionRepositoryContract::class, ApplicationMediatorSelectionRepository::class);
        $this->app->bind(ApplicationMediatorRejectionRepositoryContract::class, ApplicationMediatorRejectionRepository::class);
        $this->app->bind(ApplicationMediatorResultRepositoryContract::class, ApplicationMediatorResultRepository::class);
        $this->app->bind(MediatorApplicationCaseTypeRepositoryContract::class, MediatorApplicationCaseTypeRepository::class);

        $this->app->bind(JudgeRepositoryContract::class, JudgeRepository::class);

        $this->app->bind(IMediatorApplicationRepository::class, MediatorApplicationRepository::class);

        $this->app->bind(IExamRepository::class, ExamRepository::class);
        $this->app->bind(IMediatorExamRepository::class, MediatorExamRepository::class);
        $this->app->bind(IQualificationsRepository::class, QualificationsRepository::class);
        $this->app->bind(IMediatorCompanyRepository::class, MediatorCompanyRepository::class);
        $this->app->bind(ICitizenCompanyRepository::class, CitizenCompanyRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
