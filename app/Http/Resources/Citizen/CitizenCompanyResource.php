<?php
declare(strict_types=1);

namespace App\Http\Resources\Citizen;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitizenCompanyResource extends JsonResource
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
            'id' => $this->resource->id,
            'company_name' => $this->resource->company_name,
            'address' => $this->resource->address,
            'registration_number' => $this->resource->registration_number,
            'name_of_representative' => $this->resource->name_of_representative,
        ];
    }
}
