<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class UniqueIdGenerator
{
    /**
     * Generate a sequence-based unique ID with prefix.
     *
     * @param string $table Table name
     * @param string $prefix Prefix like GROUP, LN, BRANCH
     * @param string $column Column where UID is stored
     * @param int $padLength Minimum digits for sequence (e.g., 5 -> 00001)
     * @return string
     */
    public function generate(string $table, string $prefix, string $column = 'uid', int $padLength = 5): string
    {
        // Get last record that starts with prefix
        $lastRecord = DB::table($table)
            ->select($column)
            ->where($column, 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        if ($lastRecord && preg_match('/\d+$/', $lastRecord->$column, $matches)) {
            $number = (int) $matches[0] + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, $padLength, '0', STR_PAD_LEFT);
    }
}
