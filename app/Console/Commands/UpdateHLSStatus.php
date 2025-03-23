<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateHLSStatus extends Command
{
    protected $signature = 'video:update-hls-status';
    protected $description = 'Update HLS status for videos';

    public function handle()
    {
        Log::info('✅ UpdateHLSStatus command started.');

        try {
            // Fetch all videos where status = 1, deleted = 0
            $videos = DB::table('wellness_service.videos')
                ->where('status', 1)
                ->where('deleted', 0)
                ->get();

            $this->info('🔍 Found ' . $videos->count() . ' videos to process.');

            foreach ($videos as $video) {
                // Log video details
                $this->info("Processing Video ID: {$video->id}, Title: {$video->title}");

                // Check if hls_path is not null and not blank
                $hlsPathValid = !empty($video->hls_path) && $video->hls_path !== null;

                if ($hlsPathValid) {
                    // Directly update the database using raw query
                    DB::statement("UPDATE wellness_service.videos SET is_converted_hls_video = 1 WHERE id = ?", [$video->id]);

                    $this->info("✅ Updated Video ID: {$video->id} - HLS is ready.");
                } else {
                    DB::statement("UPDATE wellness_service.videos SET is_converted_hls_video = 0 WHERE id = ?", [$video->id]);
                }
            }

            $this->info('✅ HLS status update process completed.');
            Log::info('✅ UpdateHLSStatus command finished.');
        } catch (\Exception $e) {
            Log::error('❌ Error in UpdateHLSStatus command: ' . $e->getMessage());
        }
    }
}
