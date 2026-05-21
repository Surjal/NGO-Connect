<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNgoRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'ngo_name' => ['required', 'string', 'max:255'],
            'registration_date' => ['required', 'date'],
            'category' => ['required', 'string', 'max:255'],
            'subcategory' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'ngo_phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'logo' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'photos.*' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'registration_number' => ['required', 'string', 'max:255'],
            'registration_district' => ['required', 'string', 'max:255'],
            'last_renewal_date' => ['required', 'date'],
            'pan_number' => ['required', 'string'],
            'contact_full_name' => ['required', 'string', 'max:255'],
            'contact_position' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'contact_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'contact_password' => ['required', 'string', 'min:8', 'confirmed'],
            'contact_address' => ['required', 'string', 'max:255'],
            'declaration' => ['nullable', 'required', 'accepted'],
            'mission' => ['nullable', 'string', 'max:255'],
        ];

        return $rules;
    }
}
