<?php

namespace App\DTO;

readonly class AppointmentDTO
{

    public function __construct(
        public string $symptoms,
        public string $appointment_date,
        public string $appointment_time,
        public int    $animal_id,
        public ?int   $user_id = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'symptoms' => $this->symptoms,
            'appointment_date' => $this->appointment_date,
            'appointment_time' => $this->appointment_time,
            'animal_id' => $this->animal_id,
            'user_id' => $this->user_id ?? null,
        ];
    }
}
