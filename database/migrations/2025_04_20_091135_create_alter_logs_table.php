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
        Schema::table('api_logs', function (Blueprint $table) {
            $table->longText('response_payload')->nullable()->change();
            $table->longText('request_payload')->nullable()->change();

            // Add indexes
            $table->index('endpoint');
            $table->index('unique_visitor_id');
            $table->index('ip_address');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_logs', function (Blueprint $table) {
            Schema::dropIfExists('api_logs');;
        });
    }
};