<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query;
use Illuminate\Support\Facades\DB;

class Attachment extends Model
{
    use SoftDeletes;

    protected $table = "app_attachment";
    protected $fillable = [
        'video_id',
        'attachment_name',
        'attachment_path',
        'attachment_url',
        'added_on',
        'status',
        'deleted'
    ];


    public static function addAttchment($iVideoId, $sFileName, $sPath, $sFileUrl)
    {
        try {
            $oAttchment = self::create([
                'video_id' => $iVideoId,
                'attachment_name' => $sFileName,
                'attachment_path' => $sPath,
                'attachment_url' => $sFileUrl,
                'added_on' => now(),
            ]);

            return $oAttchment;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function removedAttchment($iRecordId)
    {
        try {
            $oAttchment = Attachment::where("id", $iRecordId)
                ->where("deleted", 0)
                ->update(['deleted' => 1]);
            return $oAttchment;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function fetchAttchmentByVideoId($iVideoId)
    {
        try {
            $oAttchment = DB::table('app_attachment AS A')
                ->leftJoin('videos AS B', 'B.id', '=', 'A.video_id')
                ->select('A.*', 'B.title')
                ->where('A.video_id', $iVideoId)
                ->where('A.status', 1)
                ->where('A.deleted', 0)
                ->where('B.status', 1)
                ->where('B.deleted', 0)
                ->get();
            return $oAttchment;
        } catch (\Exception $e) {
            throw $e;
        }
    }

}