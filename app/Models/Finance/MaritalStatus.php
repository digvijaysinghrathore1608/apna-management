<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class MaritalStatus extends Model
{
    protected $table = "finance_marital_status";
    protected $fillable = ['marital_status', 'customer_id', 'loan_id'];
}
