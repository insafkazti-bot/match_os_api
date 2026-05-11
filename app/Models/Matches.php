<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matches extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'title',
        'location',
        'match_date',
        'team_a_name',
        'team_b_name',
        'score_a',
        'score_b',
        'status',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'score_a' => 'integer',
        'score_b' => 'integer',
    ];

    public function matchTasks(): HasMany
    {
        return $this->hasMany(MatchTask::class, 'id_match');
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'match_tasks', 'id_match', 'id_task')
            ->withPivot(['id_member', 'status', 'notes', 'deadline'])
            ->withTimestamps();
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'match_tasks', 'id_match', 'id_member')
            ->withPivot(['id_task', 'status', 'notes', 'deadline'])
            ->withTimestamps();
    }
}

