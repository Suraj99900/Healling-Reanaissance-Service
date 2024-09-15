<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WellnessOtp extends Model
{
    use HasFactory;

    // Define the table name if it doesn't follow Laravel's naming convention
    protected $table = 'wellness_otp';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'otp',
        'email',
        'added_on',
        'exp_on',
        'status',
        'deleted',
    ];

    // Define the attributes that should be cast to native types
    protected $casts = [
        'added_on' => 'datetime',
        'exp_on' => 'datetime',
    ];


    public static function genrateOTP($sEmailId, $sOTP)
    {
        try {
            $otp = WellnessOtp::create([
                'otp' => $sOTP,
                'email' => $sEmailId,
                'added_on' => Carbon::now(), // Current date and time
                'exp_on' => Carbon::now()->addMinutes(60), // Date and time with 60 minutes added
                'status' => 1,
                'deleted' => 0,
            ]);
            

            return $otp;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function fetchActiveOTP($sEmailId)
    {
        try {
            $oTOP = self::where('email', $sEmailId)
                ->where('status', 1)
                ->where('deleted', 0)
                ->where('exp_on', '>', date("Y-m-d h:i:s")) // Check if the OTP is not expired
                ->first();
            return $oTOP;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function fetchActiveRecordByOTP($sOTP)
    {
        try {
            $oTOP = self::where('otp', $sOTP)
                ->where('status', 1)
                ->where('deleted', 0)
                ->where('exp_on', '>', date("Y-m-d h:i:s")) // Check if the OTP is not expired
                ->first();
            return $oTOP;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function validateOTP($sOTP)
    {
        try {
            // Find the OTP record
            $oOTP = self::where('otp', $sOTP)
                ->where('deleted', 0)
                ->where('exp_on', '>', date("Y-m-d h:i:s"))
                ->first();

            // Check if the OTP exists
            if (!$oOTP) {
                return false;
            }

            // Update the status if the OTP is valid and not expired
            $oOTP->update(['status' => 0]);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

}

