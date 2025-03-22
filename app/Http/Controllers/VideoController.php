<?php
namespace App\Http\Controllers;

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

    public function uploadVideo(Request $request)
    {
        // Validate the incoming request
        $oValidator = Validator::make($request->all(), [
            'video' => 'required|file|mimes:mp4,mov,ogg,qt,avi,flv,webm,3gp,mpeg,mpeg2,mpeg4,mkv,wmv,m4v,mxf,asf,vob,ts,tsv,mts,m2ts|max:2000000',
            'title' => 'required|string|max:25500',
            'description' => 'nullable|string',
            'category_id' => 'required|integer'
        ]);

        if ($oValidator->fails()) {
            return response()->json(['error' => $oValidator->errors()], 400);
        }

        try {
            if (!$request->hasFile('video')) {
                return response()->json([
                    'error' => 'No file uploaded',
                    'message' => "No file uploaded",
                    'status' => 400
                ], 400);
            }

            // 1) Store the original video
            $sVideoPath = $request->file('video')->store('videos', 'public');

            // If you have a thumbnail, store it too (if not, remove this):
            $sThumbnailPath = $request->file('thumbnail') 
                ? $request->file('thumbnail')->store('thumbnails', 'public')
                : null;

            // 2) Create a unique subfolder for HLS output
            // e.g. 'public/hls/<uuid>'
            $lessonId = (string) Str::uuid(); 
            $outputFolder = "hls/{$lessonId}";
            $outputAbsolutePath = Storage::disk('public')->path($outputFolder);

            if (!is_dir($outputAbsolutePath)) {
                mkdir($outputAbsolutePath, 0775, true);
            }

            // 3) Build the ffmpeg command
            // Example: ffmpeg -i {videoPath} -codec:v libx264 -codec:a aac -hls_time 10 -hls_playlist_type vod -hls_segment_filename "outputFolder/segment%03d.ts" -start_number 0 {outputFolder}/index.m3u8

            // Input file (absolute path)
            $videoAbsolutePath = Storage::disk('public')->path($sVideoPath);

            // HLS output (index.m3u8)
            $hlsFile = "{$outputAbsolutePath}/index.m3u8";
            // Segment pattern
            $segmentPattern = "{$outputAbsolutePath}/segment%03d.ts";

            // We'll use Symfony Process for better control, but you could use exec()
            $command = [
                'ffmpeg',
                '-i', $videoAbsolutePath,
                '-codec:v', 'libx264',
                '-codec:a', 'aac',
                '-hls_time', '10',
                '-hls_playlist_type', 'vod',
                '-hls_segment_filename', $segmentPattern,
                '-start_number', '0',
                $hlsFile
            ];

            $process = new Process($command);
            $process->setTimeout(3600); // e.g., 1 hour. Adjust as needed for big files.

            $process->run();

            if (!$process->isSuccessful()) {
                // If FFmpeg fails
                return response()->json([
                    'error' => 'FFmpeg failed to convert video to HLS',
                    'ffmpeg_output' => $process->getErrorOutput()
                ], 500);
            }

            // 4) The final M3U8 path will be "public/hls/<uuid>/index.m3u8"
            // We'll store it in the DB as something like 'hls/<uuid>/index.m3u8'
            $hlsRelativePath = "{$outputFolder}/index.m3u8";

            // 5) Create the video record in DB
            $videoModel = new Video;
            $video = $videoModel->addVideoDetails(
                $request->input('category_id'),
                $request->input('title'),
                $request->input('description'),
                $sVideoPath,        // Original MP4 path
                $sThumbnailPath,    // Optional thumbnail path
                '',
                "",
                "",
                $hlsRelativePath    // The M3U8 file (hls_path)
            );

            return response()->json([
                'message' => "Video uploaded & converted to HLS successfully!",
                'body'    => $video,
                'status'  => 200,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while uploading the video: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Fetch a video by ID
     */
    public function fetchById($id)
    {
        try {
            $videos = (new Video)->fetchVideoById($id);

            foreach ($videos as &$video) {
                $thumbnailPath = $video->thumbnail;
                if (!$thumbnailPath || !Storage::disk('public')->exists($thumbnailPath)) {
                    // If thumbnail does not exist, set a default URL
                    $video->thumbnail_url = "https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg";
                } else {
                    // Generate the proper URL for the thumbnail stored in the 'public' disk
                    $video->thumbnail_url = Storage::disk('public')->url($thumbnailPath);
                }

                // Assuming the videos are stored in the 'public' disk
                $video->video_url = Storage::disk('public')->url($video->path);

                if (!empty($video->hls_path) && Storage::disk('public')->exists($video->hls_path)) {
                    $video->hls_url = Storage::disk('public')->url($video->hls_path);
                } else {
                    $video->hls_url = null; // Set to null if not available
                }
            }
            if ($videos) {
                return response()->json([
                    'message' => "Video fetched successfully!",
                    'body' => $videos,
                    'status' => 200,
                ], 200);
            } else {
                return response()->json(['error' => 'Video not found'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the video: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Fetch all videos
     */
    public function fetchAll()
    {
        try {
            $videos = (new Video)->fetchAllVideos();

            foreach ($videos as &$video) {
                $thumbnailPath = $video->thumbnail;
                if (!$thumbnailPath || !Storage::disk('public')->exists($thumbnailPath)) {
                    // If thumbnail does not exist, set a default URL
                    $video->thumbnail_url = "https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg";
                } else {
                    // Generate the proper URL for the thumbnail stored in the 'public' disk
                    $video->thumbnail_url = Storage::disk('public')->url($thumbnailPath);
                }

                // Assuming the videos are stored in the 'public' disk
                $video->video_url = Storage::disk('public')->url($video->path);
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
     * Fetch all videos with pagination
     */
    public function fetchAllWithPagination(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $videos = (new Video)->fetchAllVideosWithPagination($perPage);
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
                $thumbnailPath = $video->thumbnail;
                if (!$thumbnailPath || !Storage::disk('public')->exists($thumbnailPath)) {
                    // If thumbnail does not exist, set a default URL
                    $video->thumbnail_url = "https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg";
                } else {
                    // Generate the proper URL for the thumbnail stored in the 'public' disk
                    $video->thumbnail_url = Storage::disk('public')->url($thumbnailPath);
                }

                // Assuming the videos are stored in the 'public' disk
                $video->video_url = Storage::disk('public')->url($video->path);
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


    public function fetchAllVideoDataByCategoryId($id)
    {
        try {
            $videos = (new Video)->fetchAllVideoDataByCategoryId($id);

            foreach ($videos as &$video) {
                $thumbnailPath = $video->thumbnail;
                if (!$thumbnailPath || !Storage::disk('public')->exists($thumbnailPath)) {
                    // If thumbnail does not exist, set a default URL
                    $video->thumbnail_url = "https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg";
                } else {
                    // Generate the proper URL for the thumbnail stored in the 'public' disk
                    $video->thumbnail_url = Storage::disk('public')->url($thumbnailPath);
                }

                // Assuming the videos are stored in the 'public' disk
                $video->video_url = Storage::disk('public')->url($video->path);
            }

            return response()->json([
                'message' => "Videos fetched successfully!",
                'body' => $videos,
                'status' => 200,
            ], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while Fecthing the video: ' . $e->getMessage()], 500);
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

            $path = Storage::disk('public')->path($video->path);
            if (!file_exists($path)) {
                return response()->json(['error' => 'Video file not found'], 404);
            }

            $stream = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($path) {
                $stream = fopen($path, 'rb');
                fpassthru($stream);
                fclose($stream);
            });

            $stream->headers->set('Content-Type', 'video/mp4');
            $stream->headers->set('Content-Length', Storage::disk('public')->size($video->path));

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

            $thumbnailPath = $video->thumbnail; // Assuming the column name in the database is `thumbnail_path`
            if (!$thumbnailPath || !Storage::disk('public')->exists($thumbnailPath)) {
                return response()->json([
                    'message' => 'Thumbnail URL fetched successfully!',
                    'thumbnail_url' => "https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg",
                    'status' => 200,
                ], 200);
            }

            // Generate the proper URL for the thumbnail stored in the 'public' disk
            $thumbnailUrl = Storage::disk('public')->url($thumbnailPath);

            return response()->json([
                'message' => 'Thumbnail URL fetched successfully!',
                'thumbnail_url' => $thumbnailUrl,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the thumbnail: ' . $e->getMessage()], 500);
        }
    }


}
