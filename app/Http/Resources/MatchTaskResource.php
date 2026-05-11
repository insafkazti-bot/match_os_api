<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_match' => $this->id_match,
            'id_task' => $this->id_task,
            'id_member' => $this->id_member,
            'status' => $this->status,
            'notes' => $this->notes,
            'deadline' => $this->deadline,
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
            'matches' => new MatchesResource($this->whenLoaded('matches')),
            'task' => new TaskResource($this->whenLoaded('task')),
            'member' => new MemberResource($this->whenLoaded('member')),
        ];
    }
}

