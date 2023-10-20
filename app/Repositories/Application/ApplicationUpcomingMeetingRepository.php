<?php

declare(strict_types=1);

namespace App\Repositories\Application;

use App\Enums\ApplicationMeetingStatuses;
use App\Filters\ApplicationUpcomingMeeting\DateBetweenFilter;
use App\Models\ApplicationUpcomingMeeting;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Application\IApplicationUpcomingMeetingRepository as ApplicationUpcomingMeetingRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ApplicationUpcomingMeetingRepository
    extends BaseRepository
    implements ApplicationUpcomingMeetingRepositoryContract
{
    public function __construct(ApplicationUpcomingMeeting $model)
    {
        parent::__construct($model);
    }

    public function findById(int $applicationId, int $upcomingMeetingId): ApplicationUpcomingMeeting|null
    {
        return $this->model
            ->where('id', $upcomingMeetingId)
            ->where('application_id', $applicationId)
            ->firstOrFail();
    }

    public function getUpcomingMeetingsBy(int $applicationId): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->where('date', '>', Carbon::now())
            ->whereIn('status', [
                ApplicationMeetingStatuses::CONFIRMED,
                ApplicationMeetingStatuses::UNCONFIRMED
            ])
            ->where('application_id', $applicationId)
            ->allowedFilters([
                AllowedFilter::custom('date_between', new DateBetweenFilter),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('type'),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'date',
                'created_at'
            )
            ->paginate(request()->query("per_page", 20));
    }

    public function getUpcomingMeetingsByDateRange($startDate, $endDate): Collection|array
    {
        return $this->model
            ->with('application')
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->get();
    }

    public function getMediatorUpcomingMeetings(int $id): Collection|array
    {
        return QueryBuilder::for($this->model)
            ->with('application')
            ->whereHas('application', function ($query) use ($id) {
                $query->where('mediator_id', $id);
            })
            ->allowedFilters([
                AllowedFilter::custom('date_between', new DateBetweenFilter),
            ])
            ->get();
    }


    public function getMediatorUpcomingMeetingHours(int $userId, $date): Collection|array
    {
        return $this->model
            ->select(
                DB::raw("DATE_FORMAT(start, '%H:%i') as start"),
                DB::raw("DATE_FORMAT(end, '%H:%i') as end"),

            )
            ->whereHas('application', function ($query)use ($userId){
                $query->where('mediator_id', $userId);

            })
            ->whereDate('date', $date)
            ->whereIn('status', [
                ApplicationMeetingStatuses::CONFIRMED,
                ApplicationMeetingStatuses::UNCONFIRMED
            ])
            ->orderBy('start')
            ->get();

    }


}
