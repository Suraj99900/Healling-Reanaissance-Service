<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class CheckConversionStatus extends Command
{
    protected $signature = 'video:status';
    protected $description = 'Display real-time video conversion status, queue, and system health.';

    public function handle(): void
    {
        $this->info('====================================================');
        $this->info('       VIDEO HLS CONVERSION & SYSTEM STATUS        ');
        $this->info('====================================================');

        // 1. Video Counts
        $totalVideos    = Video::count();
        $convertedCount = Video::where('is_converted_hls_video', 1)->count();
        $convertingCount= Video::where('is_converted_hls_video', 2)->count();
        $failedCount    = Video::where('is_converted_hls_video', 3)->count();
        $pendingCount   = Video::where(function ($q) {
            $q->whereNull('is_converted_hls_video')->orWhere('is_converted_hls_video', 0);
        })->count();

        $this->table(
            ['Total Videos', 'Converted (1)', 'Processing (2)', 'Failed (3)', 'Pending (0)'],
            [[$totalVideos, $convertedCount, $convertingCount, $failedCount, $pendingCount]]
        );

        // 2. Currently Converting Video Details
        if ($convertingCount > 0) {
            $activeVideos = Video::where('is_converted_hls_video', 2)->get();
            $this->info("\n--- Currently Converting Video(s) ---");
            foreach ($activeVideos as $v) {
                $segmentsCount = 0;
                $files = glob(storage_path('app/temp/hls/*/*.ts'));
                if ($files) {
                    $segmentsCount = count($files);
                }

                $this->line("  ID: {$v->id}");
                $this->line("  Title: {$v->title}");
                $this->line("  Source Path: {$v->path}");
                $this->line("  Segments generated so far: {$segmentsCount}");
                $this->line("  Last Updated: {$v->updated_at}");
            }
        }

        // 3. Queue status
        $pendingJobs = DB::table('jobs')->count();
        $failedJobs  = DB::table('failed_jobs')->count();
        $this->info("\n--- Database Queue Status ---");
        $this->line("  Pending Jobs in Queue: {$pendingJobs}");
        $this->line("  Failed Jobs in Queue:  {$failedJobs}");

        // 4. System & FFmpeg Process Status
        $this->info("\n--- FFmpeg & System Processes ---");
        $ffmpegProc = trim(shell_exec("ps aux | grep ffmpeg | grep -v grep 2>/dev/null") ?? '');
        if (! empty($ffmpegProc)) {
            $this->warn("  FFmpeg is ACTIVE:");
            $this->line("  " . substr($ffmpegProc, 0, 160) . '...');
        } else {
            $this->line("  FFmpeg is currently IDLE (no active process).");
        }

        $supervisorStatus = trim(shell_exec("supervisorctl status 2>/dev/null") ?? '');
        if (! empty($supervisorStatus)) {
            $this->info("\n--- Supervisor Status ---");
            $this->line("  " . $supervisorStatus);
        }

        $this->info("\n====================================================");
    }
}
