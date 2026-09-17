<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ConvertVideoToHLS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Video $video;

    public int $timeout = 3600; // up to 1 hour

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function handle(): void
    {
        $jobStartTime = microtime(true);

        $video = Video::find($this->video->id);
        if (! $video) {
            Log::error("[HLS Job] Video ID {$this->video->id} not found in database.");
            return;
        }

        // 1. Mark as processing (status 2) and record start time in JSON data
        $jsonData = $video->video_json_data ?? [];
        $jsonData['hls_conversion'] = [
            'status'     => 'processing',
            'started_at' => now()->toIso8601String(),
        ];
        $video->update([
            'is_converted_hls_video' => 2,
            'video_json_data'        => $jsonData,
        ]);

        $remoteKey      = $video->path; // e.g. "videos/abc123.mp4"
        $tmpDir         = storage_path('app/temp/');
        $localMp4Path   = $tmpDir . basename($remoteKey);
        $lessonId       = (string) Str::uuid();
        $hlsFolder      = storage_path("app/temp/hls/{$lessonId}");
        $ffmpegLogPath  = storage_path("logs/ffmpeg_{$video->id}.log");

        Log::info("[HLS Job #{$video->id}] Started conversion for '{$video->title}' ({$remoteKey}).");

        try {
            // STEP 1: Download original MP4 from Spaces
            $tDownloadStart = microtime(true);
            if (! is_dir($tmpDir)) {
                File::makeDirectory($tmpDir, 0777, true, true);
            }

            Log::info("[HLS Job #{$video->id}] Downloading source MP4 from Spaces disk...");
            $readStream = Storage::disk('spaces')->readStream($remoteKey);
            $writeStream = fopen($localMp4Path, 'w+b');
            if (! $readStream || ! $writeStream) {
                throw new \Exception("Could not open read/write stream for {$remoteKey}");
            }
            stream_copy_to_stream($readStream, $writeStream);
            if (is_resource($readStream)) fclose($readStream);
            if (is_resource($writeStream)) fclose($writeStream);

            $downloadElapsed = round(microtime(true) - $tDownloadStart, 2);
            $fileSizeBytes = file_exists($localMp4Path) ? filesize($localMp4Path) : 0;
            $fileSizeMB = round($fileSizeBytes / (1024 * 1024), 2);
            Log::info("[HLS Job #{$video->id}] Downloaded source MP4 ({$fileSizeMB} MB) in {$downloadElapsed}s.");

            // Probe duration immediately so UI can track progress & ETA in real-time
            try {
                $earlyDuration = $this->getVideoDuration($localMp4Path);
                if ($earlyDuration && $earlyDuration > 0) {
                    $video->update(['duration' => $earlyDuration]);
                    Log::info("[HLS Job #{$video->id}] Detected video duration: {$earlyDuration}s.");
                }
            } catch (\Throwable $e) {
                Log::warning("[HLS Job #{$video->id}] Early duration probe skipped: " . $e->getMessage());
            }

            // STEP 2: Prepare HLS output directory
            if (! is_dir($hlsFolder)) {
                File::makeDirectory($hlsFolder, 0777, true, true);
            }

            $hlsIndex       = "{$hlsFolder}/index.m3u8";
            $segmentPattern = "{$hlsFolder}/segment%03d.ts";

            // STEP 3: Check audio codec and build high-speed FFmpeg command
            $isAacAudio = false;
            try {
                $audioCodec = trim(shell_exec("ffprobe -v error -select_streams a:0 -show_entries stream=codec_name -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($localMp4Path)) ?? '');
                if (strtolower($audioCodec) === 'aac') {
                    $isAacAudio = true;
                }
            } catch (\Throwable $e) {}

            $audioArgs = $isAacAudio
                ? ['-codec:a', 'copy']
                : ['-codec:a', 'aac', '-b:a', '96k', '-ac', '2'];

            $command = array_merge([
                'nice', '-n', '19',
                'ionice', '-c', '3',
                'ffmpeg', '-y',
                '-i', $localMp4Path,
                '-threads', '1',
                '-filter_threads', '1',
                '-filter_complex_threads', '1',
                '-preset', 'superfast',
                '-vf', "scale=-2:'min(720,ih)'",
                '-codec:v', 'libx264',
                '-b:v', '900k',
                '-maxrate', '1100k',
                '-bufsize', '1500k',
                '-crf', '24',
                '-g', '60',
                '-keyint_min', '60',
                '-sn',
            ], $audioArgs, [
                '-hls_time', '10',
                '-hls_playlist_type', 'vod',
                '-hls_segment_filename', $segmentPattern,
                '-movflags', '+faststart',
                $hlsIndex,
            ]);

            $tEncodingStart = microtime(true);
            Log::info("[HLS Job #{$video->id}] FFmpeg started encoding (Live log: storage/logs/ffmpeg_{$video->id}.log)...");

            $logHandle = fopen($ffmpegLogPath, 'w');
            $process = new Process($command);
            $process->setTimeout(3600); // 1 hour max

            $process->run(function ($type, $buffer) use ($logHandle) {
                if ($logHandle) {
                    fwrite($logHandle, $buffer);
                }
            });

            if ($logHandle) {
                fclose($logHandle);
            }

            if (! $process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $encodingElapsed = round(microtime(true) - $tEncodingStart, 2);
            $segmentFiles = glob("{$hlsFolder}/*") ?: [];
            Log::info("[HLS Job #{$video->id}] FFmpeg completed in {$encodingElapsed}s. Generated " . count($segmentFiles) . " segments.");

            // STEP 4: Upload HLS files to Spaces
            $tUploadStart = microtime(true);
            $hlsRemoteFolder = "hls/{$lessonId}";
            Log::info("[HLS Job #{$video->id}] Uploading HLS segments to Spaces ({$hlsRemoteFolder})...");

            foreach ($segmentFiles as $filePath) {
                $basename = basename($filePath);
                $stream   = fopen($filePath, 'r');
                Storage::disk('spaces')->put("{$hlsRemoteFolder}/{$basename}", $stream, 'public');
                fclose($stream);
            }

            $uploadElapsed = round(microtime(true) - $tUploadStart, 2);
            Log::info("[HLS Job #{$video->id}] Uploaded all HLS files in {$uploadElapsed}s.");

            // STEP 5: Calculate Duration & Update DB record
            $duration = $this->getVideoDuration($localMp4Path);
            $totalElapsed = round(microtime(true) - $jobStartTime, 2);

            $video->refresh();
            $jsonData = $video->video_json_data ?? [];
            $jsonData['hls_conversion'] = [
                'status'           => 'completed',
                'completed_at'     => now()->toIso8601String(),
                'total_time_sec'   => $totalElapsed,
                'download_sec'     => $downloadElapsed,
                'encoding_sec'     => $encodingElapsed,
                'upload_sec'       => $uploadElapsed,
                'segments_count'   => count($segmentFiles),
                'source_size_mb'   => $fileSizeMB,
                'duration_seconds' => $duration,
            ];

            $video->update([
                'hls_path'               => "{$hlsRemoteFolder}/index.m3u8",
                'is_converted_hls_video' => 1,
                'duration'               => $duration,
                'video_json_data'        => $jsonData,
            ]);

            Log::info("[HLS Job #{$video->id}] SUCCESS! Converted in {$totalElapsed}s (HLS: {$hlsRemoteFolder}/index.m3u8, Duration: {$duration}s).");

        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            Log::error("[HLS Job #{$video->id}] FAILED: {$errorMessage}");

            // Extract last lines of ffmpeg log if available
            $lastLog = '';
            if (file_exists($ffmpegLogPath)) {
                $lastLog = substr(file_get_contents($ffmpegLogPath), -2000);
            }

            $video->refresh();
            $jsonData = $video->video_json_data ?? [];
            $jsonData['hls_conversion'] = [
                'status'    => 'failed',
                'failed_at' => now()->toIso8601String(),
                'error'     => $errorMessage,
                'last_log'  => $lastLog,
            ];

            $video->update([
                'is_converted_hls_video' => 3, // 3 = failed
                'video_json_data'        => $jsonData,
            ]);

            throw $e;

        } finally {
            // Guarantee cleanup of temporary local files
            if (isset($localMp4Path) && file_exists($localMp4Path)) {
                @unlink($localMp4Path);
            }
            if (isset($hlsFolder) && File::isDirectory($hlsFolder)) {
                File::deleteDirectory($hlsFolder);
            }
            // Keep the ffmpeg log file for inspection or remove if size is 0
            if (isset($ffmpegLogPath) && file_exists($ffmpegLogPath) && filesize($ffmpegLogPath) === 0) {
                @unlink($ffmpegLogPath);
            }
        }
    }

    private function getVideoDuration(string $filePath): ?float
    {
        $output = shell_exec("nice -n 19 ionice -c 3 ffmpeg -i " . escapeshellarg($filePath) . " 2>&1");
        if (preg_match('/Duration: (\d+):(\d+):(\d+\.\d+)/', $output, $m)) {
            return ($m[1]*3600) + ($m[2]*60) + floatval($m[3]);
        }
        return null;
    }
}
