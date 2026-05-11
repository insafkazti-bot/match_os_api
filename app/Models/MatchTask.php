<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchTask extends Model
{
    protected $fillable = ['id_match', 'id_task', 'id_member', 'status', 'notes', 'deadline'];

    public function matches(): BelongsTo
    {
        return $this->belongsTo(Matches::class, 'id_match');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'id_task');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id_member');
    }
}
