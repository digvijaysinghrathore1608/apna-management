<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class Witness extends Model
{
    protected $table = "finance_loan_witness";
    protected $fillable = ['loan_id', 'name', 'mobile', 'dob', 'guardian_name', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_pincode'];
}
