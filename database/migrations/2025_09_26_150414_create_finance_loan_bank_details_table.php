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
        Schema::create('finance_loan_bank_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')->nullable()->constrained('finance_loan_applications')->nullOnDelete();

            // Bank Info
            $table->string('account_holder_name', 100);
            $table->string('account_number', 30)->unique(); // unique account number
            $table->string('ifsc_code', 15);
            $table->string('bank_name', 100);
            $table->string('branch_name', 100)->nullable();
            $table->enum('account_type', ['savings', 'current'])->default('savings');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_loan_bank_details');
    }
};
