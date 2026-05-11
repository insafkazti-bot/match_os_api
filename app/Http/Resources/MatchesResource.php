<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'location' => $this->location,
            'match_date' => optional($this->match_date)->toISOString(),
            'team_a_name' => $this->team_a_name,
            'team_b_name' => $this->team_b_name,
            'score_a' => $this->score_a,
            'score_b' => $this->score_b,
            'status' => $this->status,
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
            'match_tasks' => MatchTaskResource::collection($this->whenLoaded('matchTasks')),
        ];
    }
}

