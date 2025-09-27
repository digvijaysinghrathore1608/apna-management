<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_service_roles')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_service_roles')
            ->withTimestamps();
    }

    public function userServiceRoles()
    {
        return $this->hasMany(UserServiceRole::class);
    }
}
