<?php

namespace App\Http\Controllers;

use App\Actions\RegisterNewAnimal;
use App\Actions\RegisterNewClient;
use App\Actions\RegisterNewClientAppointment;
use App\DTO\AnimalDTO;
use App\DTO\AppointmentDTO;
use App\DTO\ClientDTO;
use App\Enums\AppointmentPermission;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Http\Requests\Appointment\StorePublicAppointmentRequest;
use App\Http\Requests\Appointment\UpdateAppointmentRequest;
use App\Http\Resources\Appointment\AppointmentCollection;
use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Veterinary\VeterinaryResource;
use App\Models\Appointment;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{

    public function __construct(private readonly AppointmentService $appointmentService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Appointment::class);
        return Inertia::render('Admin/Appointment/Index', [
            'appointments' => new AppointmentCollection($this->appointmentService->getAll($request)),
            'headers' => $this->appointmentService->datatablesHeader(),
            'flash' => session('success')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Appointment::class);

        return Inertia::render('Admin/Appointment/Create', [
            'veterinaries' => VeterinaryResource::collection(User::veterinaries()->get()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreAppointmentRequest $request,
        RegisterNewClient       $registerNewClient,
        RegisterNewAnimal       $registerNewAnimal
    ): RedirectResponse
    {
        Gate::authorize('create', Appointment::class);

        DB::transaction(function () use ($request, $registerNewClient, $registerNewAnimal) {
            $request_validated = $request->validated();

            $client = $registerNewClient->handle(new ClientDTO(...$request_validated['client']));

            $request_validated['animal']['client_id'] = $client->id;
            $animal = $registerNewAnimal->handle(new AnimalDTO(...$request_validated['animal']));

            $request_validated['appointment']['animal_id'] = $animal->id;
            $this->appointmentService->create(new AppointmentDTO(...$request_validated['appointment']));
        });
        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully!');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function publicStore(
        StorePublicAppointmentRequest $request,
        RegisterNewClient             $registerNewClient,
        RegisterNewAnimal             $registerNewAnimal,
        RegisterNewClientAppointment  $registerNewClientAppointment
    ): RedirectResponse
    {
        DB::transaction(function () use ($request, $registerNewClient, $registerNewAnimal, $registerNewClientAppointment) {
            $request_validated = $request->validated();

            $client = $registerNewClient->handle(new ClientDTO(...$request_validated['client']));

            $request_validated['animal']['client_id'] = $client->id;
            $animal = $registerNewAnimal->handle(new AnimalDTO(...$request_validated['animal']));

            $request_validated['appointment']['animal_id'] = $animal->id;
            $registerNewClientAppointment->handle(new AppointmentDTO(...$request_validated['appointment']), $animal);
        });

        return redirect()->route('welcome.create')->with('success', 'Appointment sent successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment, Request $request): Response
    {
        Gate::authorize('view', $appointment);

        $appointment->load('animal', 'user');
        return Inertia::render('Admin/Appointment/View', [
            'appointment' => new AppointmentResource($appointment),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment): Response
    {
        Gate::authorize('update', $appointment);

        $appointment->load('animal', 'user');
        return Inertia::render('Admin/Appointment/Edit', [
            'appointment' => new AppointmentResource($appointment),
            'veterinaries' => VeterinaryResource::collection(User::veterinaries()->get()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Appointment $appointment, UpdateAppointmentRequest $request): RedirectResponse
    {
        Gate::authorize('update', $appointment);

        $this->appointmentService->update($appointment, new AppointmentDTO(...$request->validated()));
        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        Gate::authorize('delete', $appointment);

        $this->appointmentService->delete($appointment);
        return redirect()->route('appointments.index')->with('success', 'Appointment was successfully removed!');
    }
}
