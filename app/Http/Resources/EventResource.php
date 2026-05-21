<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'location' => $this->location,
            'type' => $this->type,
            'category' => $this->category,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'capacity' => $this->capacity,
            'is_volunteers_required' => $this->is_volunteers_required,
            'status' => $this->status,
            'cover_image' => $this->cover_image_path_name,
            'ngo' => NgoResource::make($this->whenLoaded('ngo')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'volunteers_count' => $this->whenCounted('volunteers'),
            'milestones' => EventMilestoneResource::collection($this->whenLoaded('milestones')),
            'created_at' => $this->created_at,
        ];
    }
}
