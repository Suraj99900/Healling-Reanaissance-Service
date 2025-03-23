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
        Schema::table('videos', function (Blueprint $table) {
            $table->boolean('is_converted_hls_video')->default(0)->after('hls_path');
        });
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

        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('is_converted_hls_video');
        });
    }
};
