<?php

declare(strict_types=1);

namespace App\Repositories\ApplicationMediator;

use App\Models\ApplicationMediatorResult;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ApplicationMediator\IApplicationMediatorResultRepository as ApplicationMediatorResultRepositoryContract;

final class ApplicationMediatorResultRepository
    extends BaseRepository
    implements ApplicationMediatorResultRepositoryContract
{
    public function __construct(ApplicationMediatorResult $model)
    {
        parent::__construct($model);
    }
}
