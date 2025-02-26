<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class UserCategoryAccess extends Model
{
    use HasFactory;

    protected $table = 'user_category_access';

    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'access_time',
        'expiration_time',
        'added_on',
        'status',
        'deleted'
    ];

    /**
     * Fetch users who have access to a given category.
     */
    public static function getUsersWithCategoryAccess($categoryId)
    {
        return self::where('category_id', $categoryId)
            ->where('expiration_time', '>', now()) // Only fetch valid access
            ->where('status', 1)
            ->where('deleted', 0)
            ->pluck('user_id'); // Return only user IDs
    }

    /**
     * Grant access to a user for a category.
     */
    public static function grantAccess($userId, $categoryId, $expirationTime)
    {
        try {
            return self::create([
                'user_id' => $userId,
                'category_id' => $categoryId,
                'access_time' => now(),
                'expiration_time' => $expirationTime,
                'added_on' => now(),
                'status' => 1,
                'deleted' => 0
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch all user-category access records.
     */
    public static function getAllAccesses()
    {
        try {
            return self::where('deleted', 0)->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch access details by ID.
     */
    public static function getAccessById($accessId)
    {
        try {
            return self::where('id', $accessId)
                ->where('deleted', 0)
                ->first();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update access details by ID.
     */
    public static function updateAccessById($accessId, $data)
    {
        try {
            $access = self::where('id', $accessId)
                ->where('deleted', 0)
                ->first();

            if ($access) {
                $access->update($data);
            }

            return $access;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete access by ID (soft delete).
     */
    public static function deleteAccessById($accessId)
    {
        try {
            $access = self::where('id', $accessId)
                ->where('deleted', 0)
                ->first();

            if ($access) {
                $access->update(['deleted' => 1]);
            }

            return $access;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch all users who have access to a specific category with details.
     */
    public static function fetchUserDetailsByCategory($categoryId)
    {
        return self::where('category_id', $categoryId)
            ->where('expiration_time', '>', now()) // Ensure access is still valid
            ->where('status', 1)
            ->where('deleted', 0)
            ->with('user') // Eager load user details
            ->get();
    }

   /**
     * Fetch users with access along with user and category details.
     *
     * @param int|null $userId
     * @param int|null $categoryId
     * @param int|null $status
     * @return mixed
     */
    public static function getUsersWithCategoryDetails($userId = null, $categoryId = null, $status = 1)
    {
        try {
            $query = DB::table('user_category_access as uca')
                ->leftJoin('wellness_users as u', 'uca.user_id', '=', 'u.id')
                ->leftJoin('video_category as vc', 'uca.category_id', '=', 'vc.id')
                ->select([
                    'uca.id as access_id',
                    'uca.access_time',
                    'uca.expiration_time',
                    'uca.status',
                    'uca.deleted',
                    'u.id as user_id',
                    'u.user_name',
                    'u.email',
                    'vc.id as category_id',
                    'vc.name as category_name',
                    'vc.description as category_description'
                ])
                ->where('uca.deleted', 0) // Ensure the access record is not deleted
                ->where('uca.expiration_time', '>', now()); // Ensure access is valid

            // Apply optional filters
            if (!is_null($userId)) {
                $query->where('uca.user_id', $userId);
            }

            if (!is_null($categoryId)) {
                $query->where('uca.category_id', $categoryId);
            }

            if (!is_null($status)) {
                $query->where('uca.status', $status);
            }

            return $query->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

}
