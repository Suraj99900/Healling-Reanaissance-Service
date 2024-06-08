<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use Exception;

class VideoController extends Controller
{
    /**
     * Upload a video
     */
    public function upload(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,ogg,qt|max:20000',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer'
        ]);

        try {
            if ($request->hasFile('video')) {
                // Store the uploaded video file
                $sVideoPath = $request->file('video')->store('videos');
                $sThumbnailPath = $request->file('thumbnail')->store('thumbnail');

                // Create a new video record in the database
                $video = (new Video)->addVideoDetails(
                    $request->input('category_id'),
                    $request->input('title'),
                    $request->input('description'),
                    $sVideoPath,
                    $sThumbnailPath,
                    ""
                );

                // Return a response with the video details
                return response()->json([
                    'message' => "Video uploaded successfully!",
                    'body' => $video,
                    'status' => 200,
                ], 200);
            }

            return response()->json([
                'error' => 'No file uploaded',
                'message' => "No file uploaded",
                'status' => 400
            ], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while uploading the video: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Fetch a video by ID
     */
    public function fetchById($id)
    {
        try {
            $video = (new Video)->fetchVideoById($id);
            if ($video) {
                return response()->json([
                    'message' => "Video fetched successfully!",
                    'body' => $video,
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
     * Update a video by ID
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->only(['title', 'description', 'status']);
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

            $path = storage_path('app/' . $video->path);
            if (!file_exists($path)) {
                return response()->json(['error' => 'Video file not found'], 404);
            }

            $stream = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($path) {
                $stream = fopen($path, 'rb');
                fpassthru($stream);
                fclose($stream);
            });

            $stream->headers->set('Content-Type', 'video/mp4');
            $stream->headers->set('Content-Length', filesize($path));

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

            $thumbnailPath = storage_path('app/' . $video->thumbnail);
            if (!file_exists($thumbnailPath)) {
                return response()->json(['error' => 'Thumbnail file not found'], 404);
            }

            $stream = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($thumbnailPath) {
                $stream = fopen($thumbnailPath, 'rb');
                fpassthru($stream);
                fclose($stream);
            });

            $stream->headers->set('Content-Type', 'image/jpeg');
            $stream->headers->set('Content-Length', filesize($thumbnailPath));

            return $stream;
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the thumbnail: ' . $e->getMessage()], 500);
        }
    }
    
}
