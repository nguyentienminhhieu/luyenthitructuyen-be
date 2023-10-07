<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens;
    use SoftDeletes;

    const ADMIN = 0;
    const STAFF = 1;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active'
    ];
    protected $guard = 'admin';
}
