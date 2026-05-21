<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NgoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ngo_name' => $this->ngo_name,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'address' => $this->address,
            'phone' => $this->phone,
            'registration_number' => $this->registration_number,
            'registration_district' => $this->registration_district,
            'pan_number' => $this->pan_number,
            'mission' => $this->mission,
            'description' => $this->description,
            'logo' => $this->logo,
            'photos' => $this->photos,
            'verified' => $this->verified,
            'suspended' => $this->suspended,
            'user' => UserResource::make($this->whenLoaded('user')),
            'followers_count' => $this->whenCounted('followers'),
            'events_count' => $this->whenCounted('events'),
            'created_at' => $this->created_at,
        ];
    }
}
