<?php

namespace App\Actions;

use App\DTO\ClientDTO;
use App\Models\Client;

class RegisterNewClient
{

    public function handle(ClientDTO $clientDTO): Client
    {
        return Client::updateOrCreate(
            ['email' => $clientDTO->email],
            $clientDTO->toArray()
        );
    }
}
