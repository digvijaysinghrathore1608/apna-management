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
        Schema::create('finance_loan_applications', function (Blueprint $table) {
            $table->id();
            $table->string('loan_id')->unique();

            $table->foreignId('customer_id')->nullable()->constrained('finance_customers')->nullOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('finance_loan_group_members')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('finance_branches')->nullOnDelete();
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('nominee')->nullable()->constrained('finance_customer_family_members')->nullOnDelete();
            $table->foreignId('loan_schema')->nullable()->constrained('finance_loan_schema')->nullOnDelete();

            $table->decimal('received_amount', 10,2)->default(0);
            $table->decimal('due_amount', 10,2)->default(0);
            $table->date('first_emi_date');
            $table->integer('emi_day');
            $table->integer('completed_cycle')->default(0);

            $table->enum('status', ['pending', 'approved', 'disbursed', 'rejected'])->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_loan_applications');
    }
};
