<?php

namespace App\Actions;

use App\DTO\AnimalDTO;
use App\Models\Animal;

class RegisterNewAnimal
{

    public function handle(AnimalDTO $animalDTO): Animal
    {
        return Animal::updateOrCreate(
            [
                'client_id' => $animalDTO->client_id,
                'name' => $animalDTO->name,
                'species' => $animalDTO->species,
            ],
            $animalDTO->toArray()
        );
    }
}
