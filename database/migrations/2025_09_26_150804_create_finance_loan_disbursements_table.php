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
        Schema::create('finance_loan_disbursements', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('loan_id')->nullable()->constrained('finance_loan_applications')->nullOnDelete();

            // Disbursement Details
            $table->enum('mode', ['cash', 'online_transfer', 'cheque'])->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('utr_number', 50)->nullable();
            $table->dateTime('disbursed_at');
            $table->foreignId('disbursed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_loan_disbursements');
    }
};
