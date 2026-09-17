<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = [
        'id',
        'video_uid',
        'category_id',
        'title',
        'description',
        'path',
        'cloudflare_video_id',
        'video_json_data',
        'hls_path',
        'is_converted_hls_video',
        'thumbnail',
        'duration',
        'added_on',
        'status',
        'deleted'
    ];

    protected $casts = [
        'video_json_data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate UUID and default added_on timestamp before creating a new record
        static::creating(function ($video) {
            if (empty($video->video_uid)) {
                $video->video_uid = (string) Str::uuid();
            }
            if (empty($video->added_on)) {
                $video->added_on = now();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(VideoCategory::class, 'category_id', 'id');
    }

    /**
     * Add video details with Cloudflare information
     */
    public function addVideoDetails($iCategoryId, $sTitle, $sDescription, $sPath, $sThumbnail, $sDuration, $sCloudflareVideoId = null, $aVideoJsonData = null, $sHlsPath = null)
    {
        try {
            $oVideo = self::create([
                'title' => $sTitle,
                'description' => $sDescription,
                'path' => $sPath,
                'thumbnail' => $sThumbnail,
                'category_id' => $iCategoryId,
                'duration' => $sDuration,
                'cloudflare_video_id' => $sCloudflareVideoId,
                'video_json_data' => $aVideoJsonData,
                'hls_path'=>$sHlsPath,
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
     * Fetch all video data by category ID (supports optional pagination)
     */
    public function fetchAllVideoDataByCategoryId($iCategoryId, $perPage = null, $page = null)
    {
        try {
            $query = DB::table('videos AS A')
                ->leftJoin('video_category AS B', 'A.category_id', '=', 'B.id')
                ->select('A.*', 'B.name')
                ->where('A.category_id', $iCategoryId)
                ->where('A.status', 1)
                ->where('A.deleted', 0)
                ->where('B.status', 1)
                ->where('B.deleted', 0)
                ->orderBy('A.added_on', 'DESC');

            if ($perPage) {
                return $query->paginate($perPage, ['*'], 'page', $page);
            }

            return $query->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Server-side paginated & searchable video list for DataTables
     */
    public function fetchVideosServerSide($start = 0, $length = 10, $search = null, $orderColumn = 0, $orderDir = 'desc')
    {
        try {
            $baseQuery = DB::table('videos AS A')
                ->leftJoin('video_category AS B', 'A.category_id', '=', 'B.id')
                ->select('A.*', 'B.name')
                ->where('A.status', 1)
                ->where('A.deleted', 0)
                ->where('B.status', 1)
                ->where('B.deleted', 0);

            $totalRecords = (clone $baseQuery)->count();

            if (!empty($search)) {
                $baseQuery->where(function ($q) use ($search) {
                    $q->where('A.title', 'like', "%{$search}%")
                      ->orWhere('B.name', 'like', "%{$search}%")
                      ->orWhere('A.id', '=', $search);
                });
            }

            $filteredRecords = (clone $baseQuery)->count();

            // Map DataTables column index to database column
            $columnMap = [
                0 => 'A.id',
                1 => 'A.title',
                2 => 'B.name',
                4 => 'A.is_converted_hls_video',
                5 => 'A.added_on',
            ];
            $col = $columnMap[$orderColumn] ?? 'A.added_on';
            $dir = strtolower($orderDir) === 'asc' ? 'asc' : 'desc';

            $data = $baseQuery->orderBy($col, $dir)
                ->skip((int)$start)
                ->take((int)$length)
                ->get();

            return [
                'total'    => $totalRecords,
                'filtered' => $filteredRecords,
                'data'     => $data,
            ];
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
            $oResult = DB::table('videos AS A')
                ->leftJoin('video_category AS B', 'A.category_id', '=', 'B.id')
                ->select('A.*', 'B.name')
                ->where('A.id', $iVideoId)
                ->where('A.status', 1)
                ->where('A.deleted', 0)
                ->where('B.status', 1)
                ->where('B.deleted', 0)
                ->get();

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
                ->where('A.deleted', 0)
                ->where('B.status', 1)
                ->where('B.deleted', 0)
                ->orderBy('A.added_on', 'DESC')
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
            $oResult = self::with('category')
                ->where('status', 1)
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
            $oResult = DB::table('videos AS A')
                ->leftJoin('video_category AS B', 'A.category_id', '=', 'B.id')
                ->select('A.*', 'B.name')
                ->where('title', 'LIKE', '%' . $sTitle . '%')
                ->where('A.status', 1)
                ->where('A.deleted', 0)
                ->where('B.status', 1)
                ->where('B.deleted', 0)
                ->get();



            return $oResult;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
