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
        Schema::rename('finance_loan_documents', 'finance_customer_documents');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('finance_customer_documents', 'finance_loan_documents');
    }
};
