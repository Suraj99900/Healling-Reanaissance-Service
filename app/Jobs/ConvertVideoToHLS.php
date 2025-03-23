<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Str;

class ConvertVideoToHLS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video;

    /**
     * Create a new job instance.
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $video = Video::find($this->video->id); // Fetch fresh instance

        if (!$video) {
            \Log::error("Video not found for ID: {$this->video->id}");
            return;
        }

        // Generate unique folder for HLS output
        $lessonId = (string) Str::uuid();
        $outputFolder = "hls/{$lessonId}";
        $outputAbsolutePath = Storage::disk('public')->path($outputFolder);

        // Ensure directory exists
        if (!is_dir($outputAbsolutePath)) {
            mkdir($outputAbsolutePath, 0775, true);
        }

        // File Paths
        $videoAbsolutePath = Storage::disk('public')->path($video->path);
        $hlsFile = "{$outputAbsolutePath}/index.m3u8";
        $segmentPattern = "{$outputAbsolutePath}/segment%03d.ts";

        // FFmpeg Command Optimized for 720p & Fast Encoding
        $command = [
            'ffmpeg',
            '-i',
            $videoAbsolutePath, // Input file

            // Force Single-Core Processing for DigitalOcean 1 vCPU
            '-threads',
            '1', // Single-threaded for single CPU
            '-preset',
            'ultrafast', // Fastest preset (lower quality but high speed)
            '-tune',
            'zerolatency', // Optimized for low latency

            // Convert to 720p Resolution
            '-vf',
            'scale=-2:720', // Maintain aspect ratio while resizing to 720p

            // Video Encoding - Fast Processing
            '-codec:v',
            'libx264',
            '-b:v',
            '1500k', // Lower bitrate for faster encoding
            '-maxrate',
            '1800k', // Peak bitrate control
            '-bufsize',
            '3000k', // Reduced buffer for efficiency
            '-crf',
            '23', // Balanced quality (lower = better, but slower)
            '-g',
            '48', // GOP size (affects keyframe interval)
            '-keyint_min',
            '48',

            // Audio Encoding
            '-codec:a',
            'aac',
            '-b:a',
            '96k', // Lower bitrate to reduce CPU usage

            // HLS Options
            '-hls_time',
            '6', // Shorter segment times for better playback experience
            '-hls_playlist_type',
            'vod',
            '-hls_segment_filename',
            $segmentPattern, // Segment filename format
            '-start_number',
            '0',

            // Optimize for Streaming
            '-movflags',
            '+faststart', // Enables progressive download

            // Output File
            $hlsFile
        ];

        $process = new Process($command);
        $process->setTimeout(3600); // 1-hour timeout

        try {
            $process->mustRun();

            // **Ensure fresh instance before update**
            // **Ensure fresh instance before update**
            $video->refresh();
            if ($video->id === $this->video->id) {
                $video->update([
                    'hls_path' => "{$outputFolder}/index.m3u8",
                    'is_converted_hls_video' => true
                ]);
            }
        } catch (ProcessFailedException $exception) {
            \Log::error("HLS Conversion Failed for Video ID: {$video->id}. Error: " . $exception->getMessage());
        }
    }
}
