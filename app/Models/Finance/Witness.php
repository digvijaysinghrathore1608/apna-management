<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class Witness extends Model
{
    protected $table = "finance_loan_witness";
    protected $fillable = ['loan_id', 'name', 'mobile', 'dob', 'guardian_name', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_pincode'];

    protected $appends = ['aadhaar_number'];

    public function documents_identities()
    {
        return $this->hasMany(DocumentIdentities::class, 'relation_id')->where('relation_table_name', 'finance_loan_witness');
    }

    public function getAadhaarNumberAttribute()
    {
        $aadhaar = $this->documents_identities
            ->where('name', 'aadhaar_number')
            ->first();
        return $aadhaar->number ?? null;
    }
}
