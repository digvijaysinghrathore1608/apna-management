<?php

namespace App\Models\Temp;

use Illuminate\Database\Eloquent\Model;

class ApnaSiteTemp extends Model
{
    protected $table = "temp_apna_stie_employee";
    protected $fillable = ['employee_id', 'content'];

    public function getContentAttribute($value)
    {
        $decoded = json_decode($value, true) ?? [];

        // Add employee_id to decoded array
        $decoded['employee_id'] = $this->employee_id;

        return $decoded;
    }
}
