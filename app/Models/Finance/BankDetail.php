<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class BankDetail extends Model
{
    protected $table="finance_loan_bank_details";
    protected $fillable =['loan_id', 'account_holder_name', 'account_number', 'ifsc_code', 'bank_name', 'branch_name', 'account_type'];
}
