<?php

declare(strict_types=1);

namespace App\Repositories\Exam;

use App\Models\Exam;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Exam\IExamRepository;

;

final class ExamRepository extends BaseRepository implements IExamRepository
{
    public function __construct(Exam $model)
    {
        parent::__construct($model);
    }

    public function getExams()
    {
        return $this->model->paginate(request()->query("per_page", 20));
    }

}
