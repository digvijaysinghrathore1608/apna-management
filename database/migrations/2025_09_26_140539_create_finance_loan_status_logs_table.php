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
        Schema::create('finance_loan_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('finance_loan_applications')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'disbursed', 'rejected']);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_loan_status_logs');
    }
};
