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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('finance_branches');
        Schema::dropIfExists('finance_customers');
        Schema::dropIfExists('finance_loan_group_members');
        Schema::dropIfExists('finance_document_identities');
        Schema::dropIfExists('finance_customer_family_members');
        Schema::dropIfExists('finance_loan_schema');
        Schema::dropIfExists('finance_loan_applications');
        Schema::dropIfExists('finance_marital_status');
        Schema::dropIfExists('finance_house_status');
        Schema::dropIfExists('finance_loan_status_logs');
        Schema::dropIfExists('finance_loan_addresses');
        Schema::dropIfExists('finance_loan_bank_details');
        Schema::dropIfExists('finance_loan_disbursements');
        Schema::dropIfExists('finance_loan_documents');
        Schema::dropIfExists('finance_loan_expenses');
        Schema::dropIfExists('finance_loan_witness');
        Schema::dropIfExists('finance_loan_statement');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
