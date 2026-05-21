<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:100',
            'preferred_categories' => 'nullable|array',
            'profile_photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }
}
