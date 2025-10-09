<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserServiceRole extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'role_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
