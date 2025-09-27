<?php

namespace App\Models\Finance;

use App\Models\BaseModel as Model;

class Branch extends Model
{
    protected $table = "finance_branches";

    protected $fillable = ['code', 'name', 'email', 'mobile', 'address', 'added_by'];
}
