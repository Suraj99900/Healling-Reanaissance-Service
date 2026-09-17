<?php
namespace App\Http\Controllers;

use App\Jobs\ConvertVideoToHLS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Exception;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;

class VideoController extends Controller
{

    public function index()
    {
        return view('video-management', [
            'cloudflareAccountId' => env('CLOUDFLARE_ACCOUNT_ID'),
            'cloudflareApiToken' => env('CLOUDFLARE_API_TOKEN'),
            'cloudflareEmail' => env('CLOUDFLARE_EMAIL')
        ]);
    }

    /**
     * Upload a video
     */
    public function upload(Request $request)
    {
        // Validate the incoming request
        $oValidator = Validator::make($request->all(), [
            'video_json_data' => 'required|json',
            'title' => 'required|string|max:25500',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
            'cloudflare_video_id' => 'required|string',
            'thumbnail' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($oValidator->fails()) {
            return response()->json(['error' => $oValidator->errors()], 400);
        }

        try {
            // Store the thumbnail file in the 'public' disk
            $sThumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');

            // Create a new video record in the database
            $video = (new Video)->addVideoDetails(
                $request->input('category_id'),
                $request->input('title'),
                $request->input('description'),
                '', // No video path since we are not uploading the video file directly
                $sThumbnailPath,
                '',
                $request->input('cloudflare_video_id'),
                json_decode($request->input('video_json_data'), true),
                ""
            );

            // Return a response with the video details
            return response()->json([
                'message' => "Video metadata saved successfully!",
                'body' => $video,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the video metadata: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Upload video in chunks
     */
    public function uploadChunk(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'video' => 'required|file',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|integer',
                'chunk_index' => 'required|integer',
                'total_chunks' => 'required|integer',
                'filename' => 'required|string',
                'thumbnail' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:5048',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Prepare chunk storage
            $file = $request->file('video');
            $chunkIndex = $request->input('chunk_index');
            $totalChunks = $request->input('total_chunks');
            $orig = pathinfo($request->input('filename'), PATHINFO_FILENAME);
            $timestamp = now()->format('Ymd_His');

            $tempDir = storage_path("app/public/videos/temp/");
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }

            // Move uploaded chunk to temp folder
            $chunkName = "{$orig}.part{$chunkIndex}";
            $file->move($tempDir, $chunkName);

            $chunkPath = "{$tempDir}{$chunkName}";
            if (!file_exists($chunkPath)) {
                return response()->json(['error' => "Chunk file not found: $chunkPath"], 500);
            }

            // If last chunk, assemble and upload
            if ($chunkIndex + 1 == $totalChunks) {
                $finalFilename = "{$orig}_{$timestamp}.mp4";
                $finalLocalPath = storage_path("app/public/videos/{$finalFilename}");
                $out = fopen($finalLocalPath, 'wb');

                // Concatenate parts
                for ($i = 0; $i < $totalChunks; $i++) {
                    $partPath = "{$tempDir}{$orig}.part{$i}";
                    fwrite($out, file_get_contents($partPath));
                    unlink($partPath);
                }
                fclose($out);

                // Upload final video via stream to DigitalOcean Spaces
                $videoStream = fopen($finalLocalPath, 'r');
                Storage::disk('spaces')->put("videos/{$finalFilename}", $videoStream, 'public');
                fclose($videoStream);

                // Remove local assembled file
                unlink($finalLocalPath);

                // Upload thumbnail (still an UploadedFile)
                $thumbPath = Storage::disk('spaces')
                    ->putFile('thumbnails', $request->file('thumbnail'), 'public');

                // Create DB record
                $video = Video::create([
                    'category_id' => $request->input('category_id'),
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'path' => "videos/{$finalFilename}",
                    'hls_path' => null,
                    'is_converted_hls_video' => false,
                    'thumbnail' => $thumbPath,
                ]);

                // Optionally dispatch HLS conversion:
                // dispatch(new ConvertVideoToHLS($video));

                return response()->json([
                    'message' => "Video uploaded successfully! HLS conversion in progress.",
                    'video' => $video,
                ], 200);
            }

            return response()->json(['message' => 'Chunk uploaded successfully.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Fetch by ID
     */
    public function fetchById($id)
    {
        try {
            $videos = (new Video)->fetchVideoById($id);

            foreach ($videos as &$video) {
                $video->thumbnail_url = $video->thumbnail
                    ?  Storage::disk('spaces')->temporaryUrl($video->thumbnail,now()->addMinutes(360))
                    : 'https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg';

                $video->video_url = Storage::disk('spaces')->temporaryUrl($video->path,now()->addMinutes(360));

               $video->hls_url = $video->hls_path
                    ? Storage::disk('spaces')->temporaryUrl(
                        $video->hls_path, now()->addMinutes(360)
                    )
                    : null;

                
            }

            return response()->json([
                'message' => "Video fetched successfully!",
                'body' => $videos,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the video: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Fetch all
     */
    /**
     * Fetch all (supports DataTables server-side pagination and fast proxy thumbnails)
     */
    public function fetchAll(Request $request)
    {
        try {
            // DataTables Server-Side Processing
            if ($request->has('draw')) {
                $start = (int) $request->input('start', 0);
                $length = (int) $request->input('length', 10);
                $search = $request->input('search.value') ?? $request->input('search');
                $orderCol = (int) $request->input('order.0.column', 0);
                $orderDir = $request->input('order.0.dir', 'desc');

                $res = (new Video)->fetchVideosServerSide($start, $length, $search, $orderCol, $orderDir);

                foreach ($res['data'] as &$video) {
                    $video->thumbnail_url = !empty($video->thumbnail)
                        ? url('/proxy-thumb') . '?file=' . urlencode($video->thumbnail)
                        : 'https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg';
                }

                return response()->json([
                    'draw'            => (int) $request->input('draw'),
                    'recordsTotal'    => $res['total'],
                    'recordsFiltered' => $res['filtered'],
                    'data'            => $res['data'],
                ], 200);
            }

            // Fast Full Fetch without slow repeated S3 SDK presigning loops
            $videos = (new Video)->fetchAllVideos();

            foreach ($videos as &$video) {
                $video->thumbnail_url = !empty($video->thumbnail)
                    ? url('/proxy-thumb') . '?file=' . urlencode($video->thumbnail)
                    : 'https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg';
                $video->video_url = null;
                $video->hls_url = null;
            }

            return response()->json([
                'message' => "Videos fetched successfully!",
                'body' => $videos,
                'status' => 200,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching videos: '
                    . $e->getMessage()
            ], 500);
        }
    }




    /**
     * Fetch all videos with pagination
     */
    public function fetchAllWithPagination(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $videos = (new Video)->fetchAllVideosWithPagination($perPage);

            foreach ($videos as &$video) {
                $video->thumbnail_url = !empty($video->thumbnail)
                    ? url('/proxy-thumb') . '?file=' . urlencode($video->thumbnail)
                    : 'https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg';
                $video->video_url = null;
                $video->hls_url = null;
            }

            return response()->json([
                'message' => "Videos fetched successfully!",
                'body' => $videos,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching videos: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Search videos by title
     */
    public function searchByTitle(Request $request)
    {
        try {
            $title = $request->input('title');
            $videos = (new Video)->searchVideosByTitle($title);

            foreach ($videos as &$video) {
                $video->thumbnail_url = !empty($video->thumbnail)
                    ? url('/proxy-thumb') . '?file=' . urlencode($video->thumbnail)
                    : 'https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg';
                $video->video_url = null;
            }

            return response()->json([
                'message' => "Videos fetched successfully!",
                'body' => $videos,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while searching for videos: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Fetch videos by Category ID with pagination
     */
    public function fetchAllVideoDataByCategoryId(Request $request, $id)
    {
        try {
            $perPage = (int) $request->input('per_page', 12);
            $page = (int) $request->input('page', 1);

            $paginated = (new Video)->fetchAllVideoDataByCategoryId($id, $perPage, $page);

            foreach ($paginated->items() as &$video) {
                $video->thumbnail_url = !empty($video->thumbnail)
                    ? url('/proxy-thumb') . '?file=' . urlencode($video->thumbnail)
                    : 'https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg';
                $video->video_url = null;
            }

            return response()->json([
                'message' => "Videos fetched successfully!",
                'body' => $paginated->items(),
                'pagination' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page'    => $paginated->lastPage(),
                    'per_page'     => $paginated->perPage(),
                    'total'        => $paginated->total(),
                    'from'         => $paginated->firstItem(),
                    'to'           => $paginated->lastItem(),
                ],
                'status' => 200,
            ], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching category videos: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a video by ID
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->only(['title', 'description', 'category_id']);
            $video = (new Video)->updateVideoById($id, $data);
            if ($video) {
                return response()->json([
                    'message' => "Video updated successfully!",
                    'body' => $video,
                    'status' => 200,
                ], 200);
            } else {
                return response()->json(['error' => 'Video not found or cannot be updated'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the video: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a video by ID
     */
    public function destroy($id)
    {
        try {
            $video = (new Video)->deleteVideoById($id);
            if ($video) {
                return response()->json([
                    'message' => "Video deleted successfully!",
                    'status' => 200,
                ], 200);
            } else {
                return response()->json(['error' => 'Video not found or cannot be deleted'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the video: ' . $e->getMessage()], 500);
        }
    }

    public function stream($id)
    {
        try {
            $video = (new Video)->fetchVideoById($id);
            if (!$video) {
                return response()->json(['error' => 'Video not found'], 404);
            }

            if (!Storage::disk('spaces')->exists($video->path)) {
                return response()->json(['error' => 'Video file not found'], 404);
            }

            $stream = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($video) {
                $readStream = Storage::disk('spaces')->readStream($video->path);
                if ($readStream) {
                    fpassthru($readStream);
                    fclose($readStream);
                }
            });

            $stream->headers->set('Content-Type', 'video/mp4');
            $stream->headers->set('Content-Length', Storage::disk('spaces')->size($video->path));

            return $stream;
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while streaming the video: ' . $e->getMessage()], 500);
        }
    }


    public function thumbnailImages($id)
    {
        try {
            $video = (new Video)->fetchVideoById($id);
            if (!$video) {
                return response()->json(['error' => 'Video not found'], 404);
            }

            $thumbnailPath = $video->thumbnail;
            if (!$thumbnailPath) {
                return response()->json([
                    'message' => 'Thumbnail URL fetched successfully!',
                    'thumbnail_url' => "https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg",
                    'status' => 200,
                ], 200);
            }

            $thumbnailUrl = Storage::disk('spaces')->temporaryUrl($thumbnailPath, now()->addMinutes(360));

            return response()->json([
                'message' => 'Thumbnail URL fetched successfully!',
                'thumbnail_url' => $thumbnailUrl,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the thumbnail: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get real-time conversion status for a specific video
     */
    public function conversionStatus($id)
    {
        try {
            $video = Video::with('category')->find($id);
            if (! $video) {
                return response()->json(['error' => 'Video not found'], 404);
            }

            $status = (int) $video->is_converted_hls_video;
            $durationSeconds = (float) $video->duration;

            // Probe duration dynamically if not yet stored and video is currently converting
            if ($durationSeconds <= 0 && $status === 2) {
                $tempFiles = glob(storage_path('app/temp/*.mp4'));
                if (! empty($tempFiles) && file_exists($tempFiles[0])) {
                    $probeOut = shell_exec("nice -n 19 ionice -c 3 ffmpeg -i " . escapeshellarg($tempFiles[0]) . " 2>&1");
                    if (preg_match('/Duration: (\d+):(\d+):(\d+\.\d+)/', $probeOut, $m)) {
                        $durationSeconds = ($m[1] * 3600) + ($m[2] * 60) + floatval($m[3]);
                        $video->update(['duration' => $durationSeconds]);
                    }
                }
            }

            // Segments count and elapsed timing
            $segmentsCount = 0;
            $elapsedSeconds = 0;
            $progressPercent = 0;
            $etaSeconds = null;
            $etaFormatted = null;
            $speedRate = null;

            if ($status === 2) {
                $files = glob(storage_path('app/temp/hls/*/*.ts'));
                $segmentsCount = $files ? count($files) : 0;

                // Elapsed time from first segment creation or updated_at
                if (! empty($files)) {
                    $firstTsTime = filemtime($files[0]);
                    $elapsedSeconds = max(1, time() - $firstTsTime);
                } elseif ($video->updated_at) {
                    $elapsedSeconds = max(1, time() - $video->updated_at->timestamp);
                }

                // If duration is known, compute exact progress and ETA
                if ($durationSeconds > 0) {
                    $totalSegmentsExpected = max(1, ceil($durationSeconds / 10));
                    $progressPercent = min(99, max(1, (int) round(($segmentsCount / $totalSegmentsExpected) * 100)));

                    // Converted video seconds so far (10s per segment)
                    $convertedVideoSec = $segmentsCount * 10;
                    if ($elapsedSeconds > 0 && $convertedVideoSec > 0) {
                        $speedRate = round($convertedVideoSec / $elapsedSeconds, 2);
                        $remainingVideoSec = max(0, $durationSeconds - $convertedVideoSec);
                        $etaSeconds = (int) round($remainingVideoSec / max(0.5, $speedRate));
                        $etaMins = floor($etaSeconds / 60);
                        $etaRemSecs = $etaSeconds % 60;
                        $etaFormatted = $etaMins > 0 ? "{$etaMins}m {$etaRemSecs}s" : "{$etaRemSecs}s";
                    }
                } else {
                    $progressPercent = min(95, max(5, $segmentsCount * 2));
                }
            } elseif ($status === 1) {
                $progressPercent = 100;
                $metadata = $video->video_json_data['hls_conversion'] ?? [];
                $segmentsCount = $metadata['segments_count'] ?? ($durationSeconds > 0 ? (int) ceil($durationSeconds / 10) : 0);
                $elapsedSeconds = $metadata['total_time_sec'] ?? null;
            }

            // Format duration
            $durationFormatted = null;
            if ($durationSeconds > 0) {
                $hrs = floor($durationSeconds / 3600);
                $mins = floor(($durationSeconds % 3600) / 60);
                $secs = round($durationSeconds % 60);
                $durationFormatted = $hrs > 0 ? "{$hrs}h {$mins}m {$secs}s" : "{$mins}m {$secs}s";
            }

            // Format elapsed
            $elapsedFormatted = null;
            if ($elapsedSeconds > 0) {
                $eMins = floor($elapsedSeconds / 60);
                $eSecs = round($elapsedSeconds % 60);
                $elapsedFormatted = $eMins > 0 ? "{$eMins}m {$eSecs}s" : "{$eSecs}s";
            }

            $ffmpegRunning = ! empty(trim(shell_exec("pgrep ffmpeg 2>/dev/null") ?? ''));

            $hlsUrl = null;
            if (! empty($video->hls_path)) {
                try {
                    $hlsUrl = Storage::disk('spaces')->temporaryUrl($video->hls_path, now()->addMinutes(360));
                } catch (\Throwable $e) {}
            }

            $sourceUrl = null;
            if (! empty($video->path)) {
                try {
                    $sourceUrl = Storage::disk('spaces')->temporaryUrl($video->path, now()->addMinutes(360));
                } catch (\Throwable $e) {}
            }

            // Thumbnail URLs: Local same-origin proxy to completely avoid CORS/COEP browser blocks
            $proxyThumbUrl = null;
            $thumbUrl = null;
            if (! empty($video->thumbnail)) {
                $proxyThumbUrl = url('/proxy-thumb') . '?file=' . urlencode($video->thumbnail);
                try {
                    $thumbUrl = Storage::disk('spaces')->temporaryUrl($video->thumbnail, now()->addMinutes(360));
                } catch (\Throwable $e) {}
            }

            // Lifecycle tracking stages
            $trackingStages = [
                [
                    'step'        => 1,
                    'title'       => 'Source Upload & Cloud Ingestion',
                    'description' => 'Original MP4 assembled and stored on DigitalOcean Spaces',
                    'status'      => 'completed',
                    'badge'       => 'Completed'
                ],
                [
                    'step'        => 2,
                    'title'       => 'Worker Queue & Resource Guard',
                    'description' => 'Supervisor queue worker (Worker 00) locked single-thread job',
                    'status'      => in_array($status, [1, 2]) ? 'completed' : ($status === 3 ? 'failed' : 'in_progress'),
                    'badge'       => in_array($status, [1, 2]) ? 'Dispatched' : ($status === 3 ? 'Failed' : 'Queued')
                ],
                [
                    'step'        => 3,
                    'title'       => '720p HLS Segment Encoding',
                    'description' => $status === 1
                        ? 'Transcoding finished (' . ($video->video_json_data['hls_conversion']['encoding_sec'] ?? 'done') . 's)'
                        : ($status === 2
                            ? "Encoding {$segmentsCount} segments ({$progressPercent}% complete, ETA: " . ($etaFormatted ?: 'calculating...') . ")"
                            : ($status === 3 ? 'Transcoding error' : 'Waiting for worker turn')),
                    'status'      => $status === 1 ? 'completed' : ($status === 2 ? 'in_progress' : ($status === 3 ? 'failed' : 'pending')),
                    'badge'       => $status === 1 ? 'Complete' : ($status === 2 ? "{$progressPercent}% Live" : ($status === 3 ? 'Failed' : 'Waiting'))
                ],
                [
                    'step'        => 4,
                    'title'       => 'HLS Stream Manifest & CDN Deployment',
                    'description' => $status === 1
                        ? 'Master playlist (.m3u8) ready for instant playback'
                        : ($status === 2 ? 'Will be published immediately upon completion' : 'Waiting for transcode'),
                    'status'      => $status === 1 ? 'completed' : ($status === 2 ? 'pending' : 'pending'),
                    'badge'       => $status === 1 ? 'Active Stream' : 'Pending'
                ]
            ];

            return response()->json([
                'status' => 200,
                'data'   => [
                    'id'                     => $video->id,
                    'title'                  => $video->title,
                    'category_name'          => $video->category ? $video->category->name : 'Uncategorized',
                    'thumbnail_url'          => $proxyThumbUrl ?: $thumbUrl,
                    'thumbnail_direct_url'   => $thumbUrl,
                    'is_converted_hls_video' => $status,
                    'hls_path'               => $video->hls_path,
                    'hls_url'                => $hlsUrl,
                    'source_path'            => $video->path,
                    'source_url'             => $sourceUrl,
                    'duration'               => $durationSeconds,
                    'duration_formatted'     => $durationFormatted,
                    'segments_count'         => $segmentsCount,
                    'progress_percent'       => $progressPercent,
                    'elapsed_seconds'        => $elapsedSeconds,
                    'elapsed_formatted'      => $elapsedFormatted,
                    'eta_seconds'            => $etaSeconds,
                    'eta_formatted'          => $etaFormatted,
                    'speed_rate'             => $speedRate,
                    'ffmpeg_running'         => $ffmpegRunning,
                    'tracking_stages'        => $trackingStages,
                    'conversion_metadata'    => $video->video_json_data['hls_conversion'] ?? null,
                    'conversion_error'       => $video->conversion_error ?? ($video->video_json_data['hls_conversion']['error'] ?? null),
                    'created_at'             => $video->created_at ? $video->created_at->toDateTimeString() : null,
                    'updated_at'             => $video->updated_at ? $video->updated_at->toDateTimeString() : null,
                ]
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Manually trigger / retry HLS conversion for a video
     */
    public function retryConversion($id)
    {
        try {
            $video = Video::find($id);
            if (! $video) {
                return response()->json(['error' => 'Video not found'], 404);
            }

            // Check if any video is actively converting
            $isBusy = Video::where('is_converted_hls_video', 2)->exists();
            if ($isBusy) {
                return response()->json([
                    'error' => 'Another video is currently converting. Only 1 conversion can run at a time to protect server stability.'
                ], 422);
            }

            // Mark as processing and dispatch
            $video->update([
                'is_converted_hls_video' => 2,
            ]);

            dispatch(new \App\Jobs\ConvertVideoToHLS($video));

            return response()->json([
                'message' => "Video ID #{$video->id} queued for HLS conversion successfully.",
                'status'  => 200
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
