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
                'thumbnail' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:5048'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Get request data
            $file = $request->file('video');
            $chunkIndex = $request->input('chunk_index');
            $totalChunks = $request->input('total_chunks');
            $originalFilename = pathinfo($request->input('filename'), PATHINFO_FILENAME);
            $timestamp = now()->format('Ymd_His'); // Format: YYYYMMDD_HHMMSS

            // Store chunks in temp directory
            $tempDir = storage_path("app/public/videos/temp/");
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }

            $chunkPath = "{$tempDir}{$originalFilename}.part{$chunkIndex}";

            // ✅ Use move() instead of file_put_contents()
            $file->move($tempDir, "{$originalFilename}.part{$chunkIndex}");

            // ✅ Ensure file is saved before reading
            sleep(1);
            if (!file_exists($chunkPath)) {
                return response()->json(['error' => "Chunk file not found: $chunkPath"], 500);
            }

            // Check if all chunks are uploaded
            if ($chunkIndex + 1 == $totalChunks) {
                $finalFilename = "{$originalFilename}_{$timestamp}.mp4";
                $finalPath = storage_path("app/public/videos/{$finalFilename}");
                $output = fopen($finalPath, 'wb');

                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkFile = "{$tempDir}{$originalFilename}.part{$i}";
                    fwrite($output, file_get_contents($chunkFile));
                    unlink($chunkFile); // Remove processed chunk
                }
                fclose($output);

                // Store thumbnail
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');

                // Save to database
                $video = Video::create([
                    'category_id' => $request->input('category_id'),
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'path' => "videos/{$finalFilename}",
                    'hls_path' => null,
                    'is_converted_hls_video' => false,
                    'thumbnail' => $thumbnailPath,
                ]);

                // Dispatch background job for HLS conversion
                // dispatch(new ConvertVideoToHLS($video));   ##  off due to overload 

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
