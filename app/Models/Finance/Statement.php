<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class Statement extends Model
{
    protected $table = "finance_loan_statement";
    protected $fillable = ['loan_id', 'type', 'demand_amount', 'recived_amount', 'due_date', 'note', 'status', 'added_by'];
}
