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
                    ? Storage::disk('spaces')->url($video->thumbnail)
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
    public function fetchAll()
    {
        try {
            $videos = (new Video)->fetchAllVideos();

            foreach ($videos as &$video) {

                // THUMBNAIL
                if (
                    !empty($video->thumbnail)
                    && Storage::disk('spaces')->exists($video->thumbnail)
                ) {
                    $video->thumbnail_url = Storage::disk('spaces')->temporaryUrl($video->thumbnail, now()->addMinutes(360));
                } else {
                    $video->thumbnail_url = 'https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg';
                }

                // VIDEO
                if (
                    !empty($video->path)
                    && Storage::disk('spaces')->exists($video->path)
                ) {
                    $video->video_url = Storage::disk('spaces')->temporaryUrl($video->path,now()->addMinutes(360));
                } else {
                    $video->video_url = null;  // or a default/fallback URL
                }
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
                if (!$thumbnailPath || !Storage::disk('spaces')->exists($thumbnailPath)) {
                    // If thumbnail does not exist, set a default URL
                    $video->thumbnail_url = "https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg";
                } else {
                    // Generate the proper URL for the thumbnail stored in the 'public' disk
                    $video->thumbnail_url = Storage::disk('spaces')->temporaryUrl($video->thumbnailPath, now()->addMinutes(360));
                }

                // Assuming the videos are stored in the 'public' disk
                $video->video_url = Storage::disk('spaces')->temporaryUrl($video->path, now()->addMinutes(360));
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
                if (!$thumbnailPath || !Storage::disk('spaces')->exists($thumbnailPath)) {
                    // If thumbnail does not exist, set a default URL
                    $video->thumbnail_url = "https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg";
                } else {
                    // Generate the proper URL for the thumbnail stored in the 'public' disk
                    $video->thumbnail_url = Storage::disk('spaces')->temporaryUrl($thumbnailPath, now()->addMinutes(360));
                }

                // Assuming the videos are stored in the 'public' disk
                $video->video_url = Storage::disk('spaces')->temporaryUrl($video->path, now()->addMinutes(360));
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

            $path = Storage::disk('spaces')->path($video->path);
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
            if (!$thumbnailPath || !Storage::disk('spaces')->exists($thumbnailPath)) {
                return response()->json([
                    'message' => 'Thumbnail URL fetched successfully!',
                    'thumbnail_url' => "https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg",
                    'status' => 200,
                ], 200);
            }

            // Generate the proper URL for the thumbnail stored in the 'public' disk
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


}
