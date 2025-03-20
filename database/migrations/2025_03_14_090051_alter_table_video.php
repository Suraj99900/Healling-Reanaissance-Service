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
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'cloudflare_video_id')) {
                $table->string('cloudflare_video_id')->nullable()->after('path');
            }
            if (!Schema::hasColumn('videos', 'video_json_data')) {
                $table->json('video_json_data')->nullable()->after('cloudflare_video_id');
            }
            if (!Schema::hasColumn('videos', 'video_uid')) {
                $table->string('video_uid')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn([
                'cloudflare_video_id',
                'video_json_data', 
                'video_uid'
            ]);
        });
    }
};