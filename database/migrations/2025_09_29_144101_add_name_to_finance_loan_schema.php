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
        Schema::table('finance_loan_schema', function (Blueprint $table) {
            $table->string('name')->index()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finance_loan_schema', function (Blueprint $table) {
            $table->dropIndex(['name']); // pehle index hatana zaruri hai
            $table->dropColumn('name');
        });
    }
};
