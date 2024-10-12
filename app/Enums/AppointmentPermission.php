<?php

namespace App\Enums;

enum AppointmentPermission: string
{
        case ATTACH = 'attach_appointment';
        case CREATE = 'create_appointment';
        case DELETE = 'delete_appointment';
        case EDIT = 'edit_appointment';
        case VIEW = 'view_appointment';
        case VIEW_ANY = 'view_any_appointment';
}
