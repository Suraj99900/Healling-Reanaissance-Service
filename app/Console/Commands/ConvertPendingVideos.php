<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Jobs\ConvertVideoToHLS;

class ConvertPendingVideos extends Command
{
    protected $signature = 'convert:pending-videos';
    protected $description = 'Find and convert a single pending video to HLS format.';

    public function handle()
    {
        $video = Video::where(function ($query) {
                $query->whereNull('is_converted_hls_video')
                    ->orWhere('is_converted_hls_video', false);
            })
            ->where(function ($query) {
                $query->whereNull('hls_path')->orWhere('hls_path', '');
            })
            ->whereNotNull('path')
            ->where('path', '!=', '')
            ->where('status', 1)
            ->where('deleted', 0)
            ->first(); // Fetch only ONE video

        if (!$video) {
            $this->info('No pending videos found for conversion.');
            return;
        }

        dispatch(new ConvertVideoToHLS($video));
        $this->info("Queued video ID {$video->id} for conversion.");
    }
}
