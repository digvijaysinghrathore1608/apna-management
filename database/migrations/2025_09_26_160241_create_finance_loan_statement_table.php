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
        Schema::create('finance_loan_statement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->nullable()->constrained('finance_loan_applications')->nullOnDelete();
            $table->enum('type', ['installment', 'due', 'rebate']);
            $table->decimal('demand_amount', 10, 2);
            $table->decimal('recived_amount', 10, 2);
            $table->date('due_date');
            $table->string('note', 50)->nullable();
            $table->enum('status', ['approved', 'rejected', 'created']);
            $table->string('added_by', 10)->default('system');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_loan_statement');
    }
};
