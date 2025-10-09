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
        Schema::table('finance_loan_documents', function (Blueprint $table) {
            // Drop old loan_id with foreign key
            if (Schema::hasColumn('finance_loan_documents', 'loan_id')) {
                $table->dropConstrainedForeignId('loan_id');
            }

            // Naya customer_id add karo
            $table->foreignId('customer_id')->after('id')
                ->nullable()
                ->constrained('finance_customers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('finance_loan_documents', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');

            $table->foreignId('loan_id')
                ->nullable()
                ->constrained('finance_loan_applications')
                ->nullOnDelete();
        });
    }
};
