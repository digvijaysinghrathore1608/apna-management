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

        // Put Employee Id first, then merge with rest of array
        $decoded = array_merge(
            ['Employee Id' => $this->employee_id],
            $decoded
        );

        return $decoded;
    }
}
