<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role_id' => $this->role_id,
            'role' => $this->whenLoaded('role', fn() => $this->role?->name),
            'verified' => $this->verified,
            'location' => $this->location,
            'profile_photo' => $this->profile_photo,
            'preferred_categories' => $this->preferred_categories,
            'ngo' => NgoResource::make($this->whenLoaded('ngo')),
            'created_at' => $this->created_at,
        ];
    }
}
