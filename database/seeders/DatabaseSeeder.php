<?php

namespace Database\Seeders;

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

        $createAppointment = Permission::updateOrCreate(['name' => 'create appointments']);
        $attachAppointment = Permission::updateOrCreate(['name' => 'attach appointments']);
        $viewAppointment = Permission::updateOrCreate(['name' => 'view appointments']);
        $editAppointment = Permission::updateOrCreate(['name' => 'edit appointments']);
        $removeAppointment = Permission::updateOrCreate(['name' => 'remove appointments']);

        $vet->syncPermissions([$viewAppointment, $editAppointment]);
        $receptionist->syncPermissions([
            $createAppointment,
            $viewAppointment,
            $editAppointment,
            $attachAppointment,
            $removeAppointment
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
