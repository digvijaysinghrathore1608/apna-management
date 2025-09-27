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
        Schema::create('finance_loan_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->nullable()->constrained('finance_loan_applications')->nullOnDelete();

            // Document Info
            $table->string('document_name', 150);
            $table->string('document_url');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_loan_documents');
    }
};
