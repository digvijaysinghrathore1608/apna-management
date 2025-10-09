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
        Schema::create('finance_loan_group_members', function (Blueprint $table) {
            $table->id();
            $table->string('group_id')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('finance_customers')->nullOnDelete();
            $table->string('area', 20);
            $table->foreignId('branch_id')->nullable()->constrained('finance_branches')->nullOnDelete();
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
        Schema::dropIfExists('finance_loan_group_members');
    }
};
