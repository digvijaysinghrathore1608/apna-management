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
        Schema::create('finance_loan_witness', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->nullable()->constrained('finance_loan_applications')->nullOnDelete();
            $table->string('name');
            $table->string('mobile',20);
            $table->date('dob');
            $table->string('guardian_name');

            $table->string('address_line_1', 50)->nullable();
            $table->string('address_line_2', 50)->nullable();
            $table->string('address_city', 50)->nullable();
            $table->string('address_state', 50)->nullable();
            $table->string('address_pincode', 50)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_loan_witness');
    }
};
