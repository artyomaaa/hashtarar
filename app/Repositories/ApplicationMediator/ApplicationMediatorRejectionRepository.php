<?php

declare(strict_types=1);

namespace App\Repositories\ApplicationMediator;

use App\Models\ApplicationMediatorRejection;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ApplicationMediator\IApplicationMediatorRejectionRepository as ApplicationMediatorRejectionRepositoryContract;

final class ApplicationMediatorRejectionRepository
    extends BaseRepository
    implements ApplicationMediatorRejectionRepositoryContract
{
    public function __construct(ApplicationMediatorRejection $model)
    {
        parent::__construct($model);
    }
}
