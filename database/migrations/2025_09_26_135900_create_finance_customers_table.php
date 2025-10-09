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
        Schema::create('finance_customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id', 10)->unique();
            
            //basic details
            $table->string('f_name', 50);
            $table->string('m_name', 50)->nullable();
            $table->string('l_name', 50)->nullable();
            $table->string('mobile', 20)->index();
            $table->string('email', 50)->nullable()->index();

            $table->date(column: 'DOB');
            $table->string('father_name');
            $table->string('mother_name');
            $table->enum('gender', ['male', 'female', 'other']);

            $table->string('in_law_father_name')->nullable();
            $table->string('in_law_mother_name')->nullable();

            $table->string('current_loan', 50)->nullable()->unique();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('inactive');
            
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('finance_branches')->nullOnDelete();
                
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_customers');
    }
};
