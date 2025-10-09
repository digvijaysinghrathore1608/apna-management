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
        Schema::create('finance_document_identities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('number')->index();
            $table->boolean('verified')->default(0);
            $table->string('relation_table_name')->index();
            $table->string('relation_id')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_document_identities');
    }
};
