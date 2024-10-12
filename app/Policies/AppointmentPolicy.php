<?php

namespace App\Policies;

use App\Enums\AppointmentPermission;
use App\Enums\RoleName;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(AppointmentPermission::VIEW_ANY->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->hasRole(RoleName::RECEPTIONIST) && $user->can(AppointmentPermission::VIEW->value)) {
            return true;
        }

        if ($user->can(AppointmentPermission::VIEW->value)) {
            return $user->id === $appointment->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(AppointmentPermission::CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        if ($user->hasRole(RoleName::RECEPTIONIST) && $user->can(AppointmentPermission::EDIT->value)) {
            return true;
        }

        if ($user->can(AppointmentPermission::EDIT->value)) {
            return $user->id === $appointment->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        if ($user->hasRole(RoleName::RECEPTIONIST) && $user->can(AppointmentPermission::DELETE->value)) {
            return true;
        }

        if ($user->can(AppointmentPermission::DELETE->value)) {
            return $user->id === $appointment->user_id;
        }

        return false;
    }
}
