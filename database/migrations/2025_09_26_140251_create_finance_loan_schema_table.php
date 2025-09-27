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
        Schema::create('finance_loan_schema', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->decimal('interest_rate', 10, 2);
            $table->decimal('insurance_amount', 10, 2);
            $table->enum('duration_type', ['year', 'month', 'day']);
            $table->integer('duration');
            $table->decimal('late_fee', 10, 2);
            $table->integer('late_fee_apply');
            $table->decimal('emi_amount', 10, 2);
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_loan_schema');
    }
};
