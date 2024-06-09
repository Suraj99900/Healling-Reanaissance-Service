<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\DB;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = [
        'id',
        'category_id',
        'title',
        'description',
        'path',
        'thumbnail',
        'duration',
        'added_on',
        'status',
        'deleted'
    ];

    /**
     * Add video details
     */
    public function addVideoDetails($iCategoryId, $sTitle, $sDescription, $sPath, $sThumbnail, $sDuration)
    {
        try {
            $oVideo = self::create([
                'title' => $sTitle,
                'description' => $sDescription,
                'path' => $sPath,
                'thumbnail' => $sThumbnail,
                'category_id' => $iCategoryId,
                'duration' => $sDuration,
                'added_on' => now(),
                'status' => 1, // Assuming 1 means active
                'deleted' => 0  // Assuming 0 means not deleted
            ]);

            return $oVideo;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch all video data by category ID
     */
    public function fetchAllVideoDataByCategoryId($iCategoryId)
    {
        try {
            $oResult = self::where('category_id', $iCategoryId)
                ->where('status', 1)
                ->where('deleted', 0)
                ->get();

            return $oResult;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch video by ID
     */
    public function fetchVideoById($iVideoId)
    {
        try {
            $oResult = self::where('id', $iVideoId)
                ->where('status', 1)
                ->where('deleted', 0)
                ->first();

            return $oResult;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update video by ID
     */
    public function updateVideoById($iVideoId, $aData)
    {
        try {
            $oVideo = self::where('id', $iVideoId)
                ->where('deleted', 0)
                ->first();

            if ($oVideo) {
                $oVideo->update($aData);
            }

            return $oVideo;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete video by ID
     */
    public function deleteVideoById($iVideoId)
    {
        try {
            $oVideo = self::where('id', $iVideoId)
                ->where('deleted', 0)
                ->first();

            if ($oVideo) {
                $oVideo->update(['deleted' => 1]);
            }

            return $oVideo;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch all videos
     */
    public function fetchAllVideos()
    {
        try {
            $oResult = DB::table('videos AS A')
                ->leftJoin('video_category AS B', 'A.category_id', '=', 'B.id')
                ->select('A.*', 'B.name')
                ->where('A.status', 1)
                ->where('B.deleted', 0)
                ->where('B.status', 1)
                ->where('B.deleted', 0)
                ->get();

            return $oResult;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Fetch all videos with pagination
     */
    public function fetchAllVideosWithPagination($iPerPage = 10)
    {
        try {
            $oResult = self::where('status', 1)
                ->where('deleted', 0)
                ->paginate($iPerPage);

            return $oResult;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Search videos by title
     */
    public function searchVideosByTitle($sTitle)
    {
        try {
            $oResult = self::where('title', 'LIKE', '%' . $sTitle . '%')
                ->where('status', 1)
                ->where('deleted', 0)
                ->get();

            return $oResult;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
