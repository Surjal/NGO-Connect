<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role_id, [1, 2]);
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable|string|max:500',
            'post_media' => 'nullable|array',
            'post_media.*' => 'image|mimes:jpg,png,jpeg|max:5120',
            'milestone_id' => 'nullable|exists:event_milestones,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->description) && !$this->hasFile('post_media')) {
                $validator->errors()->add('description', 'Either a description or at least one image is required.');
            }
        });
    }
}
