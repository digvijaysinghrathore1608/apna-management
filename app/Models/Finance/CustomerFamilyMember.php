<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class CustomerFamilyMember extends Model
{
    protected $table = "finance_customer_family_members";
    protected $fillable = ['customer_id', 'name', 'dob', 'relation', 'work_type', 'work_detail', 'monthly_income', 'mobile', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_pincode'];
}
