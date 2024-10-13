<?php

namespace App\Services;

use App\DTO\AppointmentDTO;
use App\Enums\AppointmentPermission;
use App\Models\Appointment;
use App\Notifications\AppointmentConfirmation;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AppointmentService
{
    public function getAll(Request $request): LengthAwarePaginator
    {
        $query = Appointment::with(['animal.client', 'user'])
            ->select([
                'appointments.id',
                'appointments.appointment_date',
                'appointments.appointment_time',
                'appointments.animal_id',
                'appointments.user_id',
                'appointments.symptoms'
            ]);

        $sortBy = $request->input('sortBy', 'appointment_date');
        $order = $request->input('orderBy', 'asc');

        match ($sortBy) {
            'id' => $query->orderBy('appointments.id', $order),
            'appointment_time', 'appointment_date' => $query->orderBy('appointment_date', $order)
                ->orderBy('appointment_time', $order),
            'animal.species' => $query->join('animals', 'appointments.animal_id', '=', 'animals.id')
                ->orderBy('animals.species', $order),
            'veterinary.name' => $query->join('users', 'appointments.user_id', '=', 'user.id')
                ->orderBy('user.name', $order),
            default => $query->orderBy('appointment_date')
        };

        $query->when($request->user()->isVeterinary(), function ($q) use ($request) {
            $q->where('appointments.user_id', $request->user()->id);
        });

        $query->groupBy(
            'appointments.id',
            'appointments.appointment_time',
            'appointments.appointment_date',
            'appointments.animal_id',
            'appointments.user_id',
            'appointments.symptoms'
        );

        return $query->paginate(5);
    }

    public function create(AppointmentDTO $dto): Appointment
    {
        $data = $dto->toArray();
        $data['appointment_time'] = Carbon::createFromFormat('H:i', $data['appointment_time']);

        $appointment = Appointment::create($data);

        if ($appointment->user_id) {
            $appointment->load(['user', 'animal']);
            $appointment->animal->client->notify(new AppointmentConfirmation($appointment));
        }

        return $appointment;
    }

    public function update(Appointment $appointment, AppointmentDTO $dto): Appointment
    {
        $data = $dto->toArray();
        $data['appointment_time'] = Carbon::createFromFormat('H:i', $data['appointment_time']);

        $appointment->update($data);
        $appointment->refresh();

        if ($appointment->user_id) {
            $appointment->animal->client->notify(new AppointmentConfirmation($appointment));
        }

        return $appointment;
    }

    public function delete(Appointment $appointment): void
    {
        $appointment->delete();
    }

    public function datatablesHeader(): array
    {
        return collect([
            ['title' => 'ID', 'key' => 'id', 'align' => 'start'],
            ['title' => 'Time', 'key' => 'appointment_time', 'align' => 'start'],
            ['title' => 'Date', 'key' => 'appointment_date', 'align' => 'start'],
            ['title' => 'Species', 'key' => 'animal.species', 'align' => 'start'],
            ['title' => 'Animal', 'key' => 'animal.name', 'align' => 'start', 'sortable' => false],
            ['title' => 'Client', 'key' => 'animal.owner.name', 'align' => 'start', 'sortable' => false],
            ['title' => 'Vet', 'key' => 'veterinary.name', 'align' => 'start', 'sortable' => false],
            $this->datatablesActionHeader('view'),
            $this->datatablesActionHeader('edit'),
            $this->datatablesActionHeader('delete'),
        ])->filter()->toArray();
    }

    private function datatablesActionHeader(string $title): array
    {
        if (auth()->user()->cannot(AppointmentPermission::tryFrom($title . '_appointment'))) {
            return [];
        }

        return ['title' => Str::title($title), 'key' => $title, 'align' => 'start', 'sortable' => false];
    }
}
