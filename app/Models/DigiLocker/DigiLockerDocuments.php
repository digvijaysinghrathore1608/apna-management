<?php

namespace App\Models\DigiLocker;

use Illuminate\Database\Eloquent\Model;

class DigiLockerDocuments extends Model
{
    protected $table = 'digilocker_documents';

    protected $fillable = [
        'verification_id',
        'document_name',
        'response_body',
    ];
}
