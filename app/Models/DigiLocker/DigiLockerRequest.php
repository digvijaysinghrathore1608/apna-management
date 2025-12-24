<?php

namespace App\Models\DigiLocker;

use App\Models\BaseModel;

class DigiLockerRequest extends BaseModel
{
    protected $table = 'digilocker_requests';

    protected $fillable = [
        'requester_id',
        'verification_id',
        'identify_number',
        'documents_requested',
        'request_body',
        'csf_reference_id',
        'csf_digilocker_status',
        'csf_document_consent',
        'csf_status_response_body',
    ];
}
