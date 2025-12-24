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
        Schema::create('digilocker_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('requester_id')->constrained('client_credentials')->cascadeOnDelete();
            $table->string('verification_id')->unique();  //for our system tracking
            $table->string('identify_number', 15);
            $table->string('documents_requested');
            $table->string('request_body');

            $table->string('csf_reference_id')->nullable();
            
            $table->enum('csf_digilocker_status', ['created', 'pending', 'authenticated', 'expired','consent_denied'])->default('created');
            $table->string('csf_document_consent')->nullable();
            $table->string('csf_status_response_body')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digilocker_requests');
    }
};
