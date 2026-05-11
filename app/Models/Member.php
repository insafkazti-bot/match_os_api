<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'phone', 'avatar_url', 'position'];

    protected $hidden = ['password'];

    public function matchTasks(): HasMany
    {
        return $this->hasMany(MatchTask::class, 'id_member');
    }
}
