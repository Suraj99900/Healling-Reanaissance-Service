<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateHLSStatus extends Command
{
    protected $signature = 'video:update-hls-status';
    protected $description = 'Update HLS status for videos';

    public $timeout = 5400;

    public function handle()
    {
        Log::info('✅ UpdateHLSStatus command started.');

        try {
            // Fetch all videos where status = 1, deleted = 0
            $videos = DB::table('videos')
                ->where('status', 1)
                ->where('deleted', 0)
                ->get();

            $this->info('🔍 Found ' . $videos->count() . ' videos to process.');

            foreach ($videos as $video) {
                // Do not reset status of videos that are currently processing
                if ((int)$video->is_converted_hls_video === 2) {
                    $this->info("Skipping Video ID: {$video->id} (Currently processing)");
                    continue;
                }

                // Check if hls_path is not null and not blank
                $hlsPathValid = !empty($video->hls_path) && $video->hls_path !== null;

                // Update the is_converted_hls_video column
                DB::table('videos')
                    ->where('id', $video->id)
                    ->update(['is_converted_hls_video' => $hlsPathValid ? 1 : 0]);

                $this->info("✅ Updated Video ID: {$video->id} - HLS Status: " . ($hlsPathValid ? 'Ready' : 'Pending'));
            }

            $this->info('✅ HLS status update process completed.');
            Log::info('✅ UpdateHLSStatus command finished.');
        } catch (\Exception $e) {
            Log::error('❌ Error in UpdateHLSStatus command: ' . $e->getMessage());
        }
    }
}
