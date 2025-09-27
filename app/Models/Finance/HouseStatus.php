<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class HouseStatus extends Model
{
    protected $table = "finance_house_status";
    protected $fillable = ['house_status', 'customer_id', 'loan_id'];
}
