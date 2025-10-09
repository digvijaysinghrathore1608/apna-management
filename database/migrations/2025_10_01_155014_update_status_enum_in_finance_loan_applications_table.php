<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE finance_loan_applications MODIFY status ENUM('processing', 'pending', 'approved', 'disbursed', 'rejected', 'closed') DEFAULT 'processing'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE finance_loan_applications MODIFY status ENUM('pending', 'approved', 'disbursed', 'rejected') DEFAULT 'pending'");
    }
};
