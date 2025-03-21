<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Psy\VersionUpdater\SelfUpdate;

class userComment extends Model
{
    use HasFactory;
    protected $table = 'user_comment';
    protected $fillable = [
        'video_id',
        'user_id',
        'comment',
        'added_on',
        'status',
        'deleted'
    ];


    public static function addUserComment($iVideoId, $iUserId, $sComment)
    {
        try {
            $oUserComment = self::create([
                'video_id' => $iVideoId,
                'user_id' => $iUserId,
                'comment' => $sComment,
                'added_on' => now(),
            ]);

            return $oUserComment;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function updateUserComment($sComment, $iId)
    {
        try {
            $oUserComment = self::where('id', $iId)
                ->where('status', 1)
                ->where('deleted', 0)
                ->update(['comment' => $sComment]);

            return $oUserComment;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function fetchUserCommentByVideoId($iVideoId)
    {
        try {
            $oUserComment = DB::table('user_comment as A')
                ->select('A.comment', 'C.user_name', 'C.user_type', 'C.type','A.added_on')
                ->leftJoin('videos as B', function ($join) {
                    $join->on('A.video_id', '=', 'B.id')
                        ->where('B.deleted', '=', 0)
                        ->where('B.status', '=', 1);
                })
                ->leftJoin('wellness_users as C', function ($join) {
                    $join->on('C.id', '=', 'A.user_id')
                        ->where('C.deleted', '=', 0)
                        ->where('C.status', '=', 1);
                })
                ->where('A.video_id', '=', $iVideoId)
                ->where('A.status', '=', 1)
                ->where('A.deleted', '=', 0)
                ->orderBy('A.added_on', 'desc')
                ->get();

            return $oUserComment;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function invalidUserCommentById($iId)
    {
        try {
            $oUserComment = self::where('id', $iId)
                ->where('status', 1)
                ->where('deleted', 0)
                ->update(['deleted' => 1]);

            return $oUserComment;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function fetchAllUserComment()
    {
        try {
            $oUserComment = DB::table('user_comment as A')
                ->select('A.id', 'A.comment', 'C.user_name', 'C.user_type', 'C.type','A.added_on')
                ->leftJoin('videos as B', function ($join) {
                    $join->on('A.video_id', '=', 'B.id')
                        ->where('B.deleted', '=', 0)
                        ->where('B.status', '=', 1);
                })
                ->leftJoin('wellness_users as C', function ($join) {
                    $join->on('C.id', '=', 'A.user_id')
                        ->where('C.deleted', '=', 0)
                        ->where('C.status', '=', 1);
                })
                ->where('A.status', '=', 1)
                ->where('A.deleted', '=', 0)
                ->get();
        } catch (\Exception $e) {
            throw $e;
        }

        return $oUserComment;
    }

}
