<?php

declare(strict_types=1);

namespace App\Repositories;


use App\Models\Qualification;
use App\Repositories\Contracts\IQualificationsRepository;

final class QualificationsRepository extends BaseRepository implements IQualificationsRepository
{

    public function __construct(Qualification $model)
    {
        parent::__construct($model);
    }

    public function getSpecialization(): object
    {
        return $this->model->get();
    }

}
