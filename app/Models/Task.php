<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status'];

    public function matchTasks(): HasMany
    {
        return $this->hasMany(MatchTask::class, 'id_task');
    }

    public function matches(): BelongsToMany
    {
        return $this->belongsToMany(Matches::class, 'match_tasks', 'id_task', 'id_match');
    }
}
