<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class Expenses extends Model
{
    protected $table = "finance_loan_expenses";
    protected $fillable = ['loan_id', 'expense_type', 'amount', 'remarks'];
}
