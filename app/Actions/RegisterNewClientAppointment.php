<?php

namespace App\Actions;

use App\DTO\AppointmentDTO;
use App\Http\Requests\Appointment\StorePublicAppointmentRequest;
use App\Models\Animal;
use App\Notifications\AppointmentCreated;
use App\Services\AppointmentService;
use Carbon\Carbon;

class RegisterNewClientAppointment
{

    public function handle(AppointmentDTO $appointmentDTO, Animal $animal): void
    {
        (new AppointmentService())->create($appointmentDTO);

        $animal->client->notify(new AppointmentCreated());
    }
}
