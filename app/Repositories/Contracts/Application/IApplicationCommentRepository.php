<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Application;

interface IApplicationCommentRepository
{

    public function getApplicationCommentByApplicationID(int $applicationID);
}
