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
        Schema::create('client_credentials', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('mobile', 15)->nullable();
            $table->string('email')->nullable();

            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();

            $table->string('client_id')->unique();
            $table->string('secret_key', 512);

            $table->index(['name', 'service_id']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_credentials');
    }
};
