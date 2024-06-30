<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'body',
        'added_on',
        'status',
        'deleted'
    ];

    // Create a new post
    public static function addPost($sData)
    {
        try {
            $oPostData = self::create([
                'body' => $sData,
                'added_on' => now(),
                'status' => 1,
                'deleted' => 0,
            ]);

            return $oPostData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Fetch a single post by id
    public static function fetchPost($id)
    {
        try {
            $oPostData = self::where('id', $id)->where('status', 1)->where('deleted', 0)->first();
            return $oPostData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Fetch all posts
    public static function fetchAllPosts()
    {
        try {
            $aPostData = self::where('status', 1)->where('deleted', 0)->get();
            return $aPostData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Update a post by id
    public static function updatePost($id, $sData)
    {
        try {
            $oPostData = self::where('id', $id)->where('status', 1)->where('deleted', 0)->first();
            if ($oPostData) {
                $oPostData->update([
                    'body' => $sData,
                ]);
            }
            return $oPostData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Delete a post by id (soft delete)
    public static function deletePost($id)
    {
        try {
            $oPostData = self::where('id', $id)->where('status', 1)->where('deleted', 0)->first();
            if ($oPostData) {
                $oPostData->update([
                    'deleted' => 1,
                ]);
            }
            return $oPostData;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
