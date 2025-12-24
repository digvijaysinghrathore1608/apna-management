<?php

namespace App\Models;


class ClientCredential extends BaseModel
{
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'service_id',
        'client_id',
        'secret_key'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}