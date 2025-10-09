<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class LoanStatusLog extends Model
{
    protected $table = "finance_loan_status_logs";
    protected $fillable = ['loan_id', 'status', 'updated_by'];
}
