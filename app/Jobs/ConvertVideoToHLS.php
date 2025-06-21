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
use Illuminate\Support\Facades\File;
use Aws\S3\S3Client;

class ConvertVideoToHLS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function handle(): void
    {
        $video = Video::find($this->video->id);
        if (! $video) {
            \Log::error("Video not found: {$this->video->id}");
            return;
        }

        // 1. Download original MP4 from Spaces
        $remoteKey    = $video->path; // e.g. "videos/abc123.mp4"
        $tmpDir       = storage_path('app/temp/');
        $localMp4Path = $tmpDir . basename($remoteKey);

        if (! is_dir($tmpDir)) {
            File::makeDirectory($tmpDir, 0755, true);
        }

        try {
            $contents = Storage::disk('spaces')->get($remoteKey);
            File::put($localMp4Path, $contents);
        } catch (\Exception $e) {
            \Log::error("Failed to download source MP4: {$e->getMessage()}");
            return;
        }

        // 2. Prepare HLS output folder locally
        $lessonId    = (string) Str::uuid();
        $hlsFolder   = storage_path("app/temp/hls/{$lessonId}");
        if (! is_dir($hlsFolder)) {
            File::makeDirectory($hlsFolder, 0755, true);
        }

        $hlsIndex       = "{$hlsFolder}/index.m3u8";
        $segmentPattern = "{$hlsFolder}/segment%03d.ts";

        // 3. Run FFmpeg
        $command = [
            'ffmpeg', '-i', $localMp4Path,
            '-threads', '1',
            '-preset', 'superfast', '-tune', 'zerolatency',
            '-vf', 'scale=-2:720',
            '-codec:v', 'libx264', '-b:v', '1000k', '-maxrate', '1200k', '-bufsize', '2000k', '-crf', '25',
            '-g', '50', '-keyint_min', '50',
            '-codec:a', 'aac', '-b:a', '64k', '-ac', '1',
            '-hls_time', '15', '-hls_playlist_type', 'vod',
            '-hls_segment_filename', $segmentPattern,
            '-movflags', '+faststart',
            $hlsIndex,
        ];

        $process = new Process($command);
        $process->setTimeout(3600); // up to 1 hour

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            \Log::error("FFmpeg failed: " . $e->getMessage());
            // cleanup and exit
            @unlink($localMp4Path);
            File::deleteDirectory(dirname($hlsFolder));
            return;
        }

        // 4. Upload HLS files back to Spaces
        $hlsRemoteFolder = "hls/{$lessonId}";
        foreach (glob("{$hlsFolder}/*") as $filePath) {
            $basename = basename($filePath);
            $stream   = fopen($filePath, 'r');
            Storage::disk('spaces')->put("{$hlsRemoteFolder}/{$basename}", $stream, 'public');
            fclose($stream);
        }

        // 5. Update DB record
        $duration = $this->getVideoDuration($localMp4Path);

        // 6. Cleanup local temp files
        @unlink($localMp4Path);
        File::deleteDirectory(dirname($hlsFolder));

        
        $video->refresh();
        $video->update([
            'hls_path'              => "{$hlsRemoteFolder}/index.m3u8",
            'is_converted_hls_video'=> true,
            'duration'              => $duration,
        ]);
    }

    private function getVideoDuration(string $filePath): ?float
    {
        $output = shell_exec("ffmpeg -i " . escapeshellarg($filePath) . " 2>&1");
        if (preg_match('/Duration: (\d+):(\d+):(\d+\.\d+)/', $output, $m)) {
            return ($m[1]*3600) + ($m[2]*60) + floatval($m[3]);
        }
        return null;
    }
}
