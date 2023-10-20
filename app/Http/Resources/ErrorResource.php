<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class ErrorResource extends JsonResource
{
    /**
     * Customize the outgoing response for the resource.
     *
     * @param Request
     * @param Response
     *
     * @return void
     */
    public function withResponse($request, $response): void
    {
        $response->setStatusCode(500);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request): array
    {
        self::withoutWrapping();

        return [
            'success' => false,
            'message' => $this['message'] ?? '',
        ];
    }
}
