<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('videos', 'hls_path')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->string('hls_path')->nullable()->after('video_json_data');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('videos', 'hls_path')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->dropColumn('hls_path');
            });
        }
    }
};
