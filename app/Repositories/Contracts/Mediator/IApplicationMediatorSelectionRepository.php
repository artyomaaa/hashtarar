<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Mediator;

use App\Models\Application;

interface IApplicationMediatorSelectionRepository
{
    public function getRandomMediator(Application $application);

    public function updateMediatorDetails(array $data, string $cvPath, string $avatarPath): int;
}
