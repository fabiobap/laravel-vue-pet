<?php

namespace App\Http\Resources\Animal;

use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Client\ClientResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'species' => $this->species,
            'age' => $this->age,
            'owner' => new ClientResource($this->whenLoaded('client')),
            'appointments' => AppointmentResource::collection($this->whenLoaded('appointments')),
        ];
    }
}