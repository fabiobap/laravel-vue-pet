<?php

namespace App\DTO;

readonly class AnimalDTO
{

    public function __construct(
        public string $name,
        public string $species,
        public string $birthdate,
        public int $client_id,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'species' => $this->species,
            'birthdate' => $this->birthdate,
            'client_id' => $this->client_id
        ];
    }
}
