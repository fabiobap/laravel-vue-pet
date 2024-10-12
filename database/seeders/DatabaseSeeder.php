<?php

namespace Database\Seeders;

use App\Enums\AppointmentPermission;
use App\Enums\RoleName;
use App\Models\Animal;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $vet = Role::updateOrCreate(['name' => RoleName::VET->value]);
        $receptionist = Role::updateOrCreate(['name' => RoleName::RECEPTIONIST->value]);

        $createAppointment = Permission::updateOrCreate(['name' => AppointmentPermission::CREATE->value]);
        $attachAppointment = Permission::updateOrCreate(['name' => AppointmentPermission::ATTACH->value]);
        $viewAppointment = Permission::updateOrCreate(['name' => AppointmentPermission::VIEW->value]);
        $viewAnyAppointment = Permission::updateOrCreate(['name' => AppointmentPermission::VIEW_ANY->value]);
        $editAppointment = Permission::updateOrCreate(['name' => AppointmentPermission::EDIT->value]);
        $deleteAppointment = Permission::updateOrCreate(['name' => AppointmentPermission::DELETE->value]);

        $vet->syncPermissions([$viewAppointment, $viewAnyAppointment, $editAppointment]);
        $receptionist->syncPermissions([
            $createAppointment,
            $viewAnyAppointment,
            $viewAppointment,
            $editAppointment,
            $attachAppointment,
            $deleteAppointment
        ]);

        User::factory(4)
            ->hasAttached($vet)
            ->create();
        User::factory()
            ->hasAttached($receptionist)
            ->create();

        Client::factory(10)->hasAnimals()->create();

        Appointment::factory()
            ->count(40)
            ->state(new Sequence(
                ['animal_id' => Animal::all()->random()],
                fn(Sequence $sequence) => ['animal_id' => Animal::all()->random(), 'user_id' => User::whereHas('roles', function (Builder $query) {
                    $query->where('name', '=', RoleName::VET->value);
                })->get()->random()],
            ))
            ->create();
    }
}
