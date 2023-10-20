<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Application\ApplicationActivityLogController;
use App\Http\Controllers\Application\ApplicationAttachmentController;
use App\Http\Controllers\Application\ApplicationCommentController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\Citizen\CitizenCompanyController;
use App\Http\Controllers\Exam\MediatorExamController;
use App\Http\Controllers\Judge\JudgeApplicationMediatorController;
use App\Http\Controllers\Mediator\MediatorCompaniesController;
use App\Http\Controllers\MediatorApplication\MediatorApplicationController;
use App\Http\Controllers\Application\ApplicationMeetingHistoryController;
use App\Http\Controllers\Application\ApplicationMeetingRecordingController;
use App\Http\Controllers\Application\ApplicationUpcomingMeetingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CaseType\CaseTypeController;
use App\Http\Controllers\Citizen\CitizenApplicationController;
use App\Http\Controllers\ClaimLetter\ClaimLetterController;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\Mediator\MediatorApplicationCaseTypeController;
use App\Http\Controllers\Mediator\MediatorAttachmentController;
use App\Http\Controllers\Mediator\MediatorController;
use App\Http\Controllers\Mediator\MediatorCitizenApplicationController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Specialization\SpecializationController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Judge\JudgeApplicationController;
use App\Http\Controllers\Judge\JudgeController;
use App\Http\Controllers\Court\CourtController;
use App\Http\Controllers\Mediator\MediatorJudgeApplicationController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Course\CourseLessonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/email/reset-password', [ForgotPasswordController::class, 'resetPassword']);
    Route::post('/email/forget-password', [ForgotPasswordController::class, 'forgotPassword']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('all-upcoming-meetings', [ApplicationUpcomingMeetingController::class, 'mediatorUpcomingMeetings']);

    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    Route::prefix('settings')->group(function () {
        Route::post('/password/change', [UserController::class, 'changePassword']);
        Route::post('/email/change', [UserController::class, 'changeEmail']);
        Route::post('/email/change/confirm', [UserController::class, 'changeEmailConfirm']);
        Route::post('/check-password', [UserController::class, 'checkPassword']);
        Route::put('/change-phone-number', [UserController::class, 'changePhoneNumber']);
        Route::post('/updated-mediator-cv', [MediatorController::class, 'updatedMediatorCv']);
        Route::put('/updated-mediator-specialization', [MediatorController::class, 'updatedMediatorSpecialization']);
        Route::put('/updated-mediator-institutions', [MediatorController::class, 'updatedMediatorInstitutions']);
        Route::put('/updated-other-means', [UserController::class, 'updatedOtherMeans']);
        Route::post('/upload-mediator-avatar', [MediatorController::class, 'uploadMediatorAvatar']);
        Route::post('/citizen-company', [CitizenCompanyController::class, 'store']);
    });


    Route::prefix('users')->group(function () {
        Route::post('', [UserController::class, 'store']);
        Route::get('get-user-by-ssn/{ssn}', [UserController::class, 'getBySsn']);
        Route::get('/personal-info/{id}', [UserController::class, 'getPersonalInfo']);
    });

    Route::prefix('mediators')->group(function () {
        Route::post('', [MediatorController::class, 'store']);
        Route::get('/{mediatorDetails}', [MediatorController::class, 'show']);
        Route::post('/{mediatorDetails}', [MediatorController::class, 'update']);
        Route::put('/{mediatorDetails}/status', [MediatorController::class, 'updateStatus']);
        Route::put('/{mediatorDetails}/group', [MediatorController::class, 'updateGroup']);

        // Attachments
        Route::post('/{mediatorDetails}/attachments/{attachment}/download', [MediatorAttachmentController::class, 'download']);
    });
    Route::apiResource('mediator-companies', MediatorCompaniesController::class);
    Route::prefix('mediator-companies')->group(function () {
        Route::put('{mediatorCompanyId}/status', [MediatorCompaniesController::class, 'updateStatus']);
    });

    Route::prefix('judges')->group(function () {
        Route::get('', [JudgeController::class, 'index']);
        Route::post('', [JudgeController::class, 'store']);
        Route::put('/{judgeDetails}', [JudgeController::class, 'update']);
        Route::get('/{judgeDetails}', [JudgeController::class, 'show']);
    });

    Route::prefix('citizen')->group(function () {
        Route::prefix('applications')->group(function () {
            Route::post('', [CitizenApplicationController::class, 'store']);
            Route::get('', [CitizenApplicationController::class, 'index']);
        });
    });

    Route::prefix('judge')->group(function () {
        Route::apiResource('applications', JudgeApplicationController::class, ['index','destroy','store']);
        Route::post('applications/{judgeApplicationDetail}', [JudgeApplicationController::class, 'update']);
        Route::prefix('application-mediator')->group(function () {
            Route::post('', [JudgeApplicationMediatorController::class, 'store']);
            Route::put('/{application}/status', [JudgeApplicationMediatorController::class, 'updateStatus']);
            Route::get('', [JudgeApplicationMediatorController::class, 'index']);
        });
        Route::prefix('application-mediator-accept')->group(function () {
            Route::post('/{application}/status', [JudgeApplicationMediatorController::class, 'mediatorApplicationAcceptOrReject']);
        });
    });

    Route::prefix('mediator')->group(function () {

        Route::prefix('citizen-applications')->group(function () {
            Route::get('', [MediatorCitizenApplicationController::class, 'index']);
            Route::put('/{application}/status', [MediatorCitizenApplicationController::class, 'updateStatus']);
            Route::put('/{application}/finish', [MediatorCitizenApplicationController::class, 'finish']);
        });

        Route::prefix('judge-applications')->group(function () {
            Route::get('', [MediatorJudgeApplicationController::class, 'index']);
            Route::put('{application}/status', [MediatorJudgeApplicationController::class, 'updateStatus']);
        });

        // Application case types
        Route::apiResource('application-case-types', MediatorApplicationCaseTypeController::class);
    });

    Route::prefix('claim-letters')->group(function () {
        Route::get('', [ClaimLetterController::class, 'index']);
    });

    Route::apiResource('applications', ApplicationController::class, ['store', 'index', 'show', 'destroy']);

    Route::prefix('applications')->group(function () {
        Route::post('/{application}', [ApplicationController::class, 'update']);
        Route::put('/{application}/status', [ApplicationController::class, 'updateStatus']);

        // Attachments
        Route::post('/{application}/attachments/{attachment}/download', [ApplicationAttachmentController::class, 'download']);

        //Application
        Route::post('/{application}/download', [ApplicationController::class, 'download']);

        // Comments
        Route::post('/{application}/comments', [ApplicationCommentController::class, 'store']);
        Route::get('/{application}/comments', [ApplicationCommentController::class, 'show']);

        // Meeting histories
        Route::get('/{application}/meeting-histories', [ApplicationMeetingHistoryController::class, 'index']);
        Route::post('/{application}/meeting-histories', [ApplicationMeetingHistoryController::class, 'store']);
        Route::post('/{application}/meeting-histories/{meetingStory}', [ApplicationMeetingHistoryController::class, 'update']);
        Route::delete('/{application}/meeting-histories/{meetingStory}', [ApplicationMeetingHistoryController::class, 'destroy']);


        Route::post('/{application}/meeting-histories/{meetingHistory}/recordings/{recording}/download', [ApplicationMeetingRecordingController::class, 'download']);

        // Upcoming meetings
        Route::get('/{application}/upcoming-meetings', [ApplicationUpcomingMeetingController::class, 'index']);
        Route::post('/{application}/upcoming-meetings', [ApplicationUpcomingMeetingController::class, 'store']);
        Route::put('/{application}/upcoming-meetings/{upcomingMeeting}', [ApplicationUpcomingMeetingController::class, 'update']);
        Route::put('/{application}/upcoming-meetings/{upcomingMeeting}/status', [ApplicationUpcomingMeetingController::class, 'updateStatus']);
        Route::delete('/{application}/upcoming-meetings/{upcomingMeeting}', [ApplicationUpcomingMeetingController::class, 'destroy']);

        // Activity logs
        Route::get('/{application}/activity-logs', [ApplicationActivityLogController::class, 'index']);
    });
    Route::apiResource('applications-mediator', MediatorApplicationController::class);
    Route::prefix('mediator-applications')->group(function () {
        Route::get('', [MediatorApplicationController::class, 'getAllMediatorApplications']);
        Route::get('{mediatorApplicationId}', [MediatorApplicationController::class, 'getMediatorApplication']);
        Route::put('/{application}/status', [MediatorApplicationController::class, 'updateStatus']);
    });

    Route::apiResource('case-types', CaseTypeController::class);
    Route::prefix('case-types')->group(function () {
        Route::put('{caseTypeId}/status', [CaseTypeController::class, 'updateStatus']);
    });
    Route::apiResource('courts', CourtController::class);

    Route::apiResource('courses', CourseController::class);

    Route::prefix('courses')->group(function () {
        Route::get('/{course}/mediators', [CourseController::class, 'getMediators']);
        Route::post('/{course}/mediators', [CourseController::class, 'setCourseMediators']);
        Route::get('/{course}/lessons', [CourseLessonController::class, 'index']);
        Route::post('/{course}/lessons', [CourseLessonController::class, 'store']);
        Route::get('/{course}/lessons/{courseLesson}', [CourseLessonController::class, 'show']);
        Route::get('/{course}/lessons/{courseLesson}/mediators', [CourseLessonController::class, 'getMediators']);
        Route::post('/{course}/lessons/{courseLesson}/mediators', [CourseLessonController::class, 'setMediatorsAttendanceToLesson']);
        Route::put('/{course}/lessons/{courseLesson}', [CourseLessonController::class, 'update']);
        Route::delete('/{course}/lessons/{courseLesson}', [CourseLessonController::class, 'destroy']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('', [RoleController::class, 'index']);
    });

    Route::apiResource('exams', ExamController::class);

    Route::prefix('mediator-exams')->group(function () {
        Route::get('/course/{id}', [MediatorExamController::class, 'mediatorsAccessToExam']);
        Route::post('/set-exam-result', [MediatorExamController::class, 'setExamResult']);
        Route::post('/updated-exam-result', [MediatorExamController::class, 'updateExamResult']);
    });

    Route::apiResource('specialization', SpecializationController::class);


    Route::prefix('admin')->group(function () {
        Route::get('', [AdminController::class, 'index']);
        Route::get('/employee/{id}', [AdminController::class, 'show']);
        Route::post('/employee/{id}', [AdminController::class, 'update']);
        Route::delete('/employee/{id}', [AdminController::class, 'destroy']);
        Route::post('/change-user-info', [UserController::class, 'changeUserInfo']);
        Route::post('/change-mediator-attached-documents', [MediatorController::class, 'changeMediatorAttachedDocuments']);
    });

    Route::post('mediator-free-hours', [ApplicationUpcomingMeetingController::class, 'getMediatorFreeHours']);
});

Route::prefix('mediators')->group(function () {
    Route::get('', [MediatorController::class, 'index']);
    Route::post('/{mediatorDetails}/cv/download', [MediatorController::class, 'downloadCv']);
});

Route::prefix('mediator-application')->group(function () {
    Route::post('', [MediatorApplicationController::class, 'becomeMediator']);
});
