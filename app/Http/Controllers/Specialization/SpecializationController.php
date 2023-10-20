<?php

declare(strict_types=1);

namespace App\Http\Controllers\Specialization;

use App\Http\Controllers\Controller;
use App\Http\Resources\Specialization\SpecializationResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\Contracts\IQualificationsRepository;

final class SpecializationController extends Controller
{


    public function __construct(
        private readonly IQualificationsRepository $specializationRepository,
    )
    {

    }

    public function index(): SuccessResource
    {
        return SuccessResource::make([
            'data' => SpecializationResource::collection($this->specializationRepository->getSpecialization()),
            'message' => trans('message.list-of-specializations')
        ]);
    }

}
