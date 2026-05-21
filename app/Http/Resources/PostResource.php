<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'type' => $this->type,
            'impressions' => $this->impressions,
            'is_liked' => $this->is_liked,
            'is_following' => $this->is_following,
            'reports_count' => $this->reports_count,
            'recommendation_score' => $this->recommendation_score ?? 0,
            'user' => UserResource::make($this->whenLoaded('user')),
            'medias' => MediaResource::collection($this->whenLoaded('medias')),
            'likes_count' => $this->whenCounted('likes'),
            'comments_count' => $this->whenCounted('comments'),
            'milestone' => EventMilestoneResource::make($this->whenLoaded('milestone')),
            'created_at' => $this->created_at,
        ];
    }
}
