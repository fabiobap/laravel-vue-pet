<?php

namespace App\Http\Resources\Appointment;

use App\Http\Resources\Animal\AnimalResource;
use App\Http\Resources\Veterinary\VeterinaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'appointment_date' => $this->appointment_date->format('d/m/Y'),
            'appointment_date_raw' => $this->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->appointment_time->format('g:i A'),
            'appointment_time_raw' => $this->appointment_time->format('H:i'),
            'animal' => new AnimalResource($this->whenLoaded('animal')),
            'veterinary' => new VeterinaryResource($this->whenLoaded('user')),
            'symptoms' => $this->symptoms,
        ];
    }
}
