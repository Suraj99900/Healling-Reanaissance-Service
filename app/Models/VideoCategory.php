<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\DB;

class VideoCategory extends Model
{
    use HasFactory;

    protected $table = 'video_category';

    protected $fillable = [
        'name',
        'status',
        'added_on',
        'deleted',
        'description',
    ];

    /**
     * Add video category
     */
    public static function addCategory($sName, $sDesc)
    {
        try {
            $oCategory = self::create([
                'name' => $sName,
                'description' => $sDesc,
                'added_on' => now(),
                'status' => 1,
                'deleted' => 0
            ]);

            return $oCategory;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch all categories
     */
    public static function fetchAllCategories()
    {
        try {
            $oResult = self::where('status', 1)
                ->where('deleted', 0)
                ->get();

            return $oResult;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch category by ID
     */
    public static function fetchCategoryById($iCategoryId)
    {
        try {
            $oResult = self::where('id', $iCategoryId)
                ->where('status', 1)
                ->where('deleted', 0)
                ->first();

            return $oResult;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update category by ID
     */
    public static function updateCategoryById($iCategoryId, $aData)
    {
        try {
            $oCategory = self::where('id', $iCategoryId)
                ->where('deleted', 0)
                ->first();

            if ($oCategory) {
                $oCategory->update($aData);
            }

            return $oCategory;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete category by ID
     */
    public static function deleteCategoryById($iCategoryId)
    {
        try {
            $oCategory = self::where('id', $iCategoryId)
                ->where('deleted', 0)
                ->first();

            if ($oCategory) {
                $oCategory->update(['deleted' => 1]);
            }

            return $oCategory;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch all categories with pagination
     */
    public static function fetchAllCategoriesWithPagination($iPerPage = 10)
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
     * Search categories by name
     */
    public static function searchCategoriesByName($sName)
    {
        try {
            $oResult = self::where('name', 'LIKE', '%' . $sName . '%')
                ->where('status', 1)
                ->where('deleted', 0)
                ->get();

            return $oResult;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch user category access by user ID
     */
    public static function fetchUserCategoryAccessByUserId($iUserId)
    {
        try {
            $oResult = DB::table('user_category_access as A')
                ->leftJoin('wellness_users as B', 'B.id', '=', 'A.user_id')
                ->leftJoin('video_category as C', 'C.id', '=', 'A.category_id')
                ->select('A.*', 'B.name as user_name', 'C.name as category_name')
                ->where('A.deleted', 0)
                ->where('A.status', 1)
                ->where('A.user_id', $iUserId)
                ->get();

            return $oResult;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
