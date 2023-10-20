<?php

declare(strict_types=1);

namespace App\Repositories\MediatorExam;

use App\Models\MediatorExam;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\MediatorExam\IMediatorExamRepository;


final class MediatorExamRepository extends BaseRepository implements IMediatorExamRepository
{
    public function __construct(MediatorExam $model)
    {
        parent::__construct($model);
    }

    public function updatedExamResult($data): bool
    {
        foreach ($data['examResult'] as $value) {
            $this->model->where('id',$value['result_id'])->update([
                'exam_result' => $value['exam_result'],
                'qualifications' => json_encode($data['qualifications']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return true;
    }

}
