<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client'=>['required','array'],
            'client.name'=> ['required', 'string', 'max:255'],
            'client.email' => ['required', 'string', 'email', 'max:255'],
            'animal'=>['required','array'],
            'animal.name' => ['required', 'string', 'max:255'],
            'animal.species' => ['required', 'string', 'max:255'],
            'animal.birthdate'=> ['required', 'date'],
            'appointment'=>['required','array'],
            'appointment.appointment_date' => ['required', 'date'],
            'appointment.appointment_time' => ['required', 'date_format:H:i'],
            'appointment.user_id' => ['nullable', 'integer', 'exists:users,id'],
            'appointment.symptoms' => ['required', 'string']
        ];
    }
}
