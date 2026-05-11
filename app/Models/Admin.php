<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'avatar_url'];

    protected $hidden = ['password'];
}
