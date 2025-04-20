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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('unique_visitor_id')->nullable(); // Unique visitor identifier
            $table->string('method'); // HTTP method (GET, POST, etc.)
            $table->string('endpoint'); // API endpoint
            $table->json('request_payload')->nullable(); // Request data
            $table->json('response_payload')->nullable(); // Response data
            $table->integer('status_code'); // HTTP status code
            $table->ipAddress('ip_address'); // Client IP address
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
