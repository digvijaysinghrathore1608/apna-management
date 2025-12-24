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
        Schema::create('digilocker_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verification_id')->constrained('digilocker_requests')->cascadeOnDelete();
            $table->string('document_name');
            $table->text('response_body');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digilocker_documents');
    }
};
