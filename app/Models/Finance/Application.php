<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class Application extends Model
{
    protected $table = "finance_loan_applications";
    protected $fillable = ['loan_id', 'customer_id', 'group_id', 'branch_id', 'added_by', 'nominee', 'loan_schema', 'received_amount', 'due_amount', 'first_emi_date', 'emi_day', 'completed_cycle', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function bank_detail()
    {
        return $this->hasOne(BankDetail::class, 'loan_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'loan_id');
    }
}
