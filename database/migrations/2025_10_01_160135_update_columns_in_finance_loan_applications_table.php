<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('finance_loan_applications', function (Blueprint $table) {
            $table->date('first_emi_date')->nullable()->default(null)->change();
            $table->integer('emi_day')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finance_loan_applications', function (Blueprint $table) {
            //
        });
    }
};
