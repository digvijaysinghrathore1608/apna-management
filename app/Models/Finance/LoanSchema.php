<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class LoanSchema extends Model
{
    protected $table = "finance_loan_schema";
    protected $fillable = ['amount', 'interest_rate', 'insurance_amount', 'duration_type', 'duration', 'late_fee', 'late_fee_apply', 'emi_amount', 'added_by'];
}
