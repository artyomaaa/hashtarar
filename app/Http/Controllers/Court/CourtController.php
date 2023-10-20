<?php

namespace App\Http\Controllers\Court;

use App\Http\Requests\Court\DestroyCourtRequest;
use App\Http\Requests\Court\GetCourtRequest;
use App\Http\Requests\Court\GetCourtsRequest;
use App\Http\Requests\Court\StoreCourtRequest;
use App\Http\Requests\Court\UpdateCourtRequest;
use App\Http\Resources\Court\CourtResource;
use App\Http\Resources\SuccessResource;
use App\Models\Court;
use App\Repositories\Contracts\ICourtRepository;

class CourtController
{
    public function __construct(
        private ICourtRepository $courtRepository,
    )
    {

    }

    public function index(GetCourtsRequest $request): SuccessResource
    {
        return SuccessResource::make([
            'data' => CourtResource::collection($this->courtRepository->getCourts()),
        ]);
    }

    public function store(StoreCourtRequest $request): SuccessResource
    {
        $court = $this->courtRepository->create($request->validated());

        return SuccessResource::make([
            'data' => CourtResource::make($court),
            'message' => trans('message.court-created')
        ]);
    }

    public function show(GetCourtRequest $request, Court $court): SuccessResource
    {
        return SuccessResource::make([
            'data' => CourtResource::make($court),
        ]);
    }

    public function destroy(DestroyCourtRequest $request, Court $court): SuccessResource
    {
        $this->courtRepository->delete($court->id);

        return SuccessResource::make([
            'message' => trans('message.court-archived')
        ]);
    }

    public function update(UpdateCourtRequest $request, Court $court): SuccessResource
    {
        $this->courtRepository->update($court->id, $request->validated());

        return SuccessResource::make([
            'message' => trans('message.court-updated')
        ]);
    }
}
