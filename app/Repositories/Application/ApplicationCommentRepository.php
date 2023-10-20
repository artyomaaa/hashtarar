<?php

declare(strict_types=1);

namespace App\Repositories\Application;

use App\Models\ApplicationComment;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Application\IApplicationCommentRepository as CitizenApplicationCommentRepositoryContract;

final class ApplicationCommentRepository
    extends BaseRepository
    implements CitizenApplicationCommentRepositoryContract
{
    public function __construct(ApplicationComment $model)
    {
        parent::__construct($model);
    }

    public function getApplicationCommentByApplicationID(int $applicationID): object
    {
        return $this->model->where('application_id',$applicationID)->get();
    }

}
