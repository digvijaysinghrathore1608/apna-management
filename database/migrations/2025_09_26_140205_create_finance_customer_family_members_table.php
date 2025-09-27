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
        Schema::create('finance_customer_family_members', function (Blueprint $table) {
            $table->id();

            // Relation with customer
            $table->foreignId('customer_id')->constrained('finance_customers')->cascadeOnDelete();

            $table->string('name', 100);
            $table->date('dob')->nullable();       
            $table->string('relation', 50);            
            $table->enum('work_type', ['salaried', 'business', 'student', 'housewife', 'retired', 'other'])->nullable();
            $table->string('work_detail', 150)->nullable();
            $table->decimal('monthly_income', 12, 2)->nullable(); 
            
            $table->string('mobile', 20)->nullable();
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
        Schema::dropIfExists('finance_customer_family_members');
    }
};
