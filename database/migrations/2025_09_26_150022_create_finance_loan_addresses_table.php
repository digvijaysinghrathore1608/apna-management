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
        Schema::create('finance_loan_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->nullable()->constrained('finance_loan_applications')->nullOnDelete();
            // Current Address
            $table->string('c_a_line_1', 100)->nullable();
            $table->string('c_a_line_2', 100)->nullable();
            $table->string('c_a_pincode', 10)->nullable();
            $table->string('c_a_city', 50)->nullable();
            $table->string('c_a_state', 50)->nullable();

            // in law Address
            $table->string('in_law_a_line_1', 100)->nullable();
            $table->string('i_law_a_line_2', 100)->nullable();
            $table->string('i_law_a_pincode', 10)->nullable();
            $table->string('i_law_a_city', 50)->nullable();
            $table->string('i_law_a_state', 50)->nullable();

            // Permanent Address
            $table->string('p_a_line_1', 100)->nullable();
            $table->string('p_a_line_2', 100)->nullable();
            $table->string('p_a_pincode', 10)->nullable();
            $table->string('p_a_city', 50)->nullable();
            $table->string('p_a_state', 50)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_loan_addresses');
    }
};
