<?php
declare(strict_types=1);

namespace App\Http\Resources\Mediator;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediatorCompanyResource extends JsonResource
{


    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->resource->id,
            'company_name' => $this->resource->company_name,
            'status' => $this->resource->status,
        ];
    }
}
