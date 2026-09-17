<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Jobs\ConvertVideoToHLS;
use Illuminate\Support\Facades\DB;

class ConvertPendingVideos extends Command
{
    protected $signature = 'convert:pending-videos';
    protected $description = 'Find and convert a single pending video to HLS format.';

    public function handle()
    {
        // 0. Auto-recover stale jobs: if any video is marked processing (2) for > 2 hours and no ffmpeg process exists
        $staleVideos = Video::where('is_converted_hls_video', 2)
            ->where('updated_at', '<=', now()->subHours(2))
            ->get();

        if ($staleVideos->isNotEmpty()) {
            $ffmpegRunning = ! empty(trim(shell_exec("pgrep ffmpeg 2>/dev/null") ?? ''));
            if (! $ffmpegRunning) {
                foreach ($staleVideos as $staleVideo) {
                    \Log::warning("[ConvertPendingVideos] Video ID {$staleVideo->id} was stuck in processing state without an active FFmpeg process. Marking as failed (3).");
                    $staleVideo->update(['is_converted_hls_video' => 3]);
                }
            }
        }

        // 1. First check if any video is actively converting
        $currentlyProcessing = Video::where('is_converted_hls_video', 2)->exists();
        if ($currentlyProcessing) {
            $this->info('A video is currently being converted. Waiting for completion.');
            return;
        }

        // Also check if any job is currently queued in database jobs table
        $pendingJobCount = DB::table('jobs')->count();
        if ($pendingJobCount > 0) {
            $this->info('A job is already in the queue. Waiting for it to run.');
            return;
        }

        // 2. Find the oldest pending video
        $video = Video::where(function ($query) {
                $query->whereNull('is_converted_hls_video')
                    ->orWhere('is_converted_hls_video', 0);
            })
            ->where(function ($query) {
                $query->whereNull('hls_path')->orWhere('hls_path', '');
            })
            ->whereNotNull('path')
            ->where('path', '!=', '')
            ->where('status', 1)
            ->where('deleted', 0)
            ->where('created_at', '<=', now()->subHours(1))
            ->orderBy('id', 'asc')
            ->first();

        if (! $video) {
            $this->info('No pending videos found for conversion.');
            return;
        }

        // Mark immediately as processing (2) so subsequent cron runs do not re-dispatch
        $video->update(['is_converted_hls_video' => 2]);

        dispatch(new ConvertVideoToHLS($video));
        $this->info("Marked video ID {$video->id} as processing and dispatched to queue.");
    }
}
