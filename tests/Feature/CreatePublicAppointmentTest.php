<?php

namespace Tests\Feature;

use App\Actions\RegisterNewAnimal;
use App\Actions\RegisterNewClient;
use App\DTO\AnimalDTO;
use App\DTO\ClientDTO;
use App\Models\Appointment;
use App\Models\Client;
use App\Notifications\AppointmentCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CreatePublicAppointmentTest extends TestCase
{
    use RefreshDatabase;

    private const PUBLIC_CORRECT_FORM = [
        'client' => [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ],
        'animal' => [
            'name' => 'José',
            'birthdate' => '2018-01-01',
            'species' => 'Dog',
        ],
        'appointment' => [
            'appointment_date' => '2024-10-18',
            'appointment_time' => '10:00',
            'symptoms' => 'Coughing',
        ],
    ];

    public function test_public_create_appointment_form_successfully()
    {
        $response = $this->post(route('public.appointments.store'), self::PUBLIC_CORRECT_FORM);

        $response->assertStatus(302);
        $response->assertRedirect(route('welcome.create'));

        $this->assertDatabaseHas('clients', ['name' => 'John Doe']);
        $this->assertDatabaseHas('animals', ['name' => 'José']);
        $this->assertDatabaseHas('appointments', ['symptoms' => 'Coughing']);
    }

    public function test_public_create_appointment_form_fails()
    {
        $response = $this->post(route('public.appointments.store'), [
            'client' => [
                'name' => '',
                'email' => 'invalid-email',
            ],
            'animal' => [
                'name' => '',
                'birthdate' => '',
                'species' => '',
            ],
            'appointment' => [
                'appointment_date' => '',
                'appointment_time' => '',
                'symptoms' => '',
            ],
        ]);

        $response->assertRedirect(route('welcome.create'));
        $response->assertSessionHasErrors(['client.name', 'client.email', 'animal.name', 'appointment.appointment_date']);
    }

    public function test_check_if_only_appointment_is_being_created_if_client_is_the_same()
    {
        Client::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $response = $this->post(route('public.appointments.store'), self::PUBLIC_CORRECT_FORM);

        $response->assertStatus(302);
        $response->assertRedirect(route('welcome.create'));

        $this->assertDatabaseCount('clients', 1);
        $this->assertDatabaseHas('clients', ['name' => 'John Doe']);
        $this->assertDatabaseHas('animals', ['name' => 'José']);
        $this->assertDatabaseHas('appointments', ['symptoms' => 'Coughing']);
    }

    public function test_check_action_register_new_client()
    {
        $client = (new RegisterNewClient())->handle(new ClientDTO(
            ...self::PUBLIC_CORRECT_FORM['client']
        ));

        $this->assertDatabaseHas('clients', ['name' => 'John Doe']);
    }
    public function test_check_action_register_new_animal()
    {
        $client = (new RegisterNewClient())->handle(new ClientDTO(
            ...self::PUBLIC_CORRECT_FORM['client']
        ));

        (new RegisterNewAnimal())->handle(new AnimalDTO(
            ...self::PUBLIC_CORRECT_FORM['animal'] + ['client_id' => $client->id]
        ));

        $this->assertDatabaseHas('clients', ['name' => 'John Doe']);
        $this->assertDatabaseHas('animals', ['name' => 'José']);

    }

    public function test_check_if_appointment_notification_is_sending_correctly()
    {
        $client = (new RegisterNewClient())->handle(new ClientDTO(
            ...self::PUBLIC_CORRECT_FORM['client']
        ));

        $animal = (new RegisterNewAnimal())->handle(new AnimalDTO(
            ...self::PUBLIC_CORRECT_FORM['animal'] + ['client_id' => $client->id]
        ));

        Appointment::factory()->create([
            'appointment_date' => '2024-10-18',
            'appointment_time' => '10:00',
            'symptoms' => 'Coughing',
            'animal_id' => $animal->id,
        ]);

        Notification::fake();

        $animal->client->notify(new AppointmentCreated());

        Notification::assertSentTo($client, AppointmentCreated::class, function ($notification, $channels) use ($client) {
            $this->assertContains('mail', $channels);

            $mailNotification = $notification->toMail($client);
            $this->assertEquals('Your appointment was received!', $mailNotification->subject);
            $this->assertEquals(
                'This is just an confirmation that your appointment was received.',
                $mailNotification->introLines[0]
            );
            $this->assertEquals(
                'As soon as our professionals analyse the date you sent, and assign you the best veterinary :)! We will send a confirmation email when it\'s ready.',
                $mailNotification->introLines[1]
            );
            $this->assertEquals('Thank you for using our application!', $mailNotification->introLines[2]);

            return true;
        });
    }
}
