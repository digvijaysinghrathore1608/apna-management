<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class Address extends Model
{
    protected $table ="finance_loan_addresses";
    protected $fillable =['loan_id', 'c_a_line_1', 'c_a_line_2', 'c_a_pincode', 'c_a_city', 'c_a_state', 'in_law_a_line_1', 'i_law_a_line_2', 'i_law_a_pincode', 'i_law_a_city', 'i_law_a_state', 'p_a_line_1', 'p_a_line_2', 'p_a_pincode', 'p_a_city', 'p_a_state'];
}
