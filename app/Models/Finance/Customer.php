<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class Customer extends Model
{
    protected $table = "finance_customers";
    protected $fillable = [
        'customer_id',
        'f_name',
        'm_name',
        'l_name',
        'mobile',
        'email',
        'DOB',
        'father_name',
        'mother_name',
        'gender',
        'in_law_father_name',
        'in_law_mother_name',
        'current_loan',
        'status',
        'added_by',
        'branch_id'
    ];

    public function getFullNameAttribute(): string
    {
        return trim($this->f_name . ' ' . $this->m_name . ' ' . $this->l_name);
    }
    public function getFormatedDobAttribute(): string
    {
        return \Carbon\Carbon::parse($this->dob)->format('d-m-Y');
    }


    //relations

    public function document_identities()
    {
        return $this->hasMany(DocumentIdentities::class, 'relation_id')
            ->where('relation_table_name', $this->getTable());
    }

    public function family_members()
    {
        return $this->hasMany(CustomerFamilyMember::class, 'customer_id');
    }
}
