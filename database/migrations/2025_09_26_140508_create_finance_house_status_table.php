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
        Schema::create('finance_house_status', function (Blueprint $table) {
            $table->id();
            $table->enum('house_status', ['rent', 'self_owned', 'leased', 'company_provided']);
            $table->foreignId('customer_id')->nullable()->constrained('finance_customers')->nullOnDelete();
            $table->foreignId('loan_id')->nullable()->constrained('finance_loan_applications')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // ✅ Ensure unique combination of customer_id + loan_id
            $table->unique(['customer_id', 'loan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_house_status');
    }
};
