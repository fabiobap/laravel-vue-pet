<?php

namespace App\Http\Resources\Veterinary;

use App\Http\Resources\Appointment\AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VeterinaryResource extends JsonResource
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
            'email' => $this->email,
            'appointments' => AppointmentResource::collection($this->whenLoaded('appointments')),
        ];
    }
}
