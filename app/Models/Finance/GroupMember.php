<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class GroupMember extends Model
{
    protected $table = "finance_loan_group_members";

    protected $fillable = ['group_id', 'customer_id', 'area', 'branch_id', 'added_by'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function getNameAttribute(): string
    {
        return $this->customer->full_name;
    }
}
