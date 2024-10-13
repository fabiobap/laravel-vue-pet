<?php

namespace App\DTO;

readonly class ClientDTO
{

    public function __construct(
        public string $name,
        public string $email,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
