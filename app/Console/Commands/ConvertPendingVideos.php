<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Jobs\ConvertVideoToHLS;

class ConvertPendingVideos extends Command
{
    protected $signature = 'convert:pending-videos';
    protected $description = 'Find and convert all pending videos to HLS format.';

    public function handle()
    {
        $videos = Video::whereNull('is_converted_hls_video')->orWhere('is_converted_hls_video', false)->whereNull('hls_path')
            ->get();

        if ($videos->isEmpty()) {
            $this->info('No videos found for conversion.');
            return;
        }

        foreach ($videos as $video) {
            dispatch(new ConvertVideoToHLS($video));
            $this->info("Queued video ID {$video->id} for conversion.");
        }

        $this->info('All pending videos have been queued for HLS conversion.');
    }
}
