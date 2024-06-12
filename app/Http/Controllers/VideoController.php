<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Exception;

class VideoController extends Controller
{
    /**
     * Upload a video
     */
    public function upload(Request $request)
    {
        // Validate the incoming request
        $oValidator = Validator::make($request->all(), [
            'video' => 'required|file|mimes:mp4,mov,ogg,qt|max:2000000',
            'title' => 'required|string|max:25500',
            'description' => 'nullable|string',
            'category_id' => 'required|integer'
        ]);

        if ($oValidator->fails()) {
            return response()->json(['error' => $oValidator->errors()], 400);
        }

        try {
            if ($request->hasFile('video')) {
                // Store the uploaded video file in the 'public' disk
                $sVideoPath = $request->file('video')->store('videos', 'public');

                // Store the thumbnail file in the 'public' disk
                $sThumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');

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
