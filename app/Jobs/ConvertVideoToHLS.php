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
            '-i', $videoAbsolutePath, // Input file
        
            // Single-Core Processing (Limit CPU Usage)
            '-threads', '1',  // Force single-threaded processing
        
            // Fast Encoding
            '-preset', 'superfast', // Slightly better than 'ultrafast' but still fast
            '-tune', 'zerolatency', // Optimize for real-time processing
        
            // Resize to 720p (Maintain Aspect Ratio)
            '-vf', 'scale=-2:720',
        
            // Efficient Video Encoding
            '-codec:v', 'libx264',
            '-b:v', '1000k',  // Lower bitrate to reduce CPU and storage usage
            '-maxrate', '1200k',  // Peak bitrate control
            '-bufsize', '2000k',  // Reduce memory usage
            '-crf', '25',  // Adjust quality (higher = lower quality, but faster)
            '-g', '50', // GOP size (adjust for better keyframe placement)
            '-keyint_min', '50',
        
            // Lower Audio Processing Load
            '-codec:a', 'aac',
            '-b:a', '64k', // Lower bitrate to save CPU & storage
            '-ac', '1', // Convert to mono (reduces processing)
        
            // HLS Streaming Settings
            '-hls_time', '15', // Slightly longer segments to reduce CPU overhead
            '-hls_playlist_type', 'vod',
            '-hls_segment_filename', $segmentPattern, // HLS Segment naming
            '-start_number', '0',
        
            // Optimize for Low Bandwidth & Storage
            '-movflags', '+faststart',
        
            // Output File
            $hlsFile
        ];
        

        $process = new Process($command);
        $process->setTimeout(9600); // 1-hour timeout

        try {
            $process->mustRun();

            // ✅ Fetch the video duration after conversion
            $videoDuration = $this->getVideoDuration($videoAbsolutePath);

            // ✅ Ensure fresh instance before update
            $video->refresh();
            $video->update([
                'hls_path' => "{$outputFolder}/index.m3u8",
                'is_converted_hls_video' => true,
                'duration' => $videoDuration, // ✅ Update duration in DB
            ]);
        } catch (ProcessFailedException $exception) {
            \Log::error("HLS Conversion Failed for Video ID: {$video->id}. Error: " . $exception->getMessage());
        }
    }

    /**
     * Get the duration of the video using FFmpeg
     */
    private function getVideoDuration($filePath)
    {
        try {
            $cmd = "ffmpeg -i " . escapeshellarg($filePath) . " 2>&1";
            $output = shell_exec($cmd);

            if (preg_match('/Duration: (\d+):(\d+):(\d+\.\d+)/', $output, $matches)) {
                $hours = (int) $matches[1];
                $minutes = (int) $matches[2];
                $seconds = (float) $matches[3];
                return ($hours * 3600) + ($minutes * 60) + $seconds;
            }

            return null; // Return null if duration is not found
        } catch (\Exception $e) {
            \Log::error("Error getting video duration: " . $e->getMessage());
            return null;
        }
    }
}
