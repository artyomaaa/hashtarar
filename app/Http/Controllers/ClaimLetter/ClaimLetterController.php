<?php

namespace App\Http\Controllers\ClaimLetter;

use App\Http\Requests\ClaimLetter\GetClaimLettersRequest;
use App\Http\Resources\Application\ApplicationResource;
use App\Http\Resources\PaginationResource;
use App\Repositories\Contracts\Application\IApplicationRepository;

class ClaimLetterController
{
    public function __construct(
        private IApplicationRepository $applicationRepository,
    )
    {

    }

    public function index(GetClaimLettersRequest $request): PaginationResource
    {
        $applications = $this->applicationRepository->getClaimLetters();

        return PaginationResource::make([
            'data' => ApplicationResource::collection($applications->items()),
            'pagination' => $applications
        ]);
    }
}
