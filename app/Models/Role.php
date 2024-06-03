<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Role extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $guard_name = 'api';
    protected $guarded = [];

    // public function users()
    // {
    //     return $this->belongsToMany('App\Models\User');
    // }s

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class);
    // }
}
