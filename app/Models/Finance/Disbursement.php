<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class Disbursement extends Model
{
    protected $table ="finance_loan_disbursements";
    protected $fillable =['loan_id', 'mode', 'bank_name', 'utr_number', 'disbursed_at', 'disbursed_by'];
}
