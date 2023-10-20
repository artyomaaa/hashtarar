<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Application;

interface IApplicationRepository
{
    public function getCitizenApplicationsBy(int $citizenId);

    public function getJudgeApplicationsBy(int $judgeOrMediatorId);

    public function getMediatorApplicationsBy(int $mediatorId);

    public function getMediatorClaimLettersBy(int $mediatorId);

    public function getApplications();

    public function getClaimLetters();

    public function findById(int $applicationId);
}
