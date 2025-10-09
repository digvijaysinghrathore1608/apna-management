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
        Schema::create('finance_loan_expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')->nullable()->constrained('finance_loan_applications')->nullOnDelete();
            $table->string('expense_type', 100);
            $table->decimal('amount', 12, 2);  
            $table->text('remarks')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_loan_expenses');
    }
};
