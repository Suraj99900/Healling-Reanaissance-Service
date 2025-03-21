<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'wellness_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'user_type',
        'type',
        'added_on',
        'status',
        'deleted'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public static function addUser($sUserName, $sEmail, $sPassword, $iUserType, $sType)
    {
        try {
            $oUser = self::create([
                'user_name' => $sUserName,
                'email' => $sEmail,
                'password' => $sPassword,
                'user_type' => $iUserType,
                'type' => $sType,
                'added_on' => now(),
            ]);

            return $oUser;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function checkUserPresent($sEmail)
    {
        try {
            $oUser = self::where('email', $sEmail)
                ->where('status', 1)
                ->where('deleted', 0)
                ->first();

            if (isset($oUser)) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function fetchUserByEmail($sEmail)
    {
        try {
            $oUser = self::select('*')
                ->where('email', $sEmail)
                ->where('status', 1)
                ->where('deleted', 0)
                ->first();

            return $oUser;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function updateUserByEmailId($sEmail, $sPassword)
    {
        try {
            $oUser = self::where('email', $sEmail)
                ->where('status', 1)
                ->where('deleted', 0)
                ->update(['password' => $sPassword]);

            if ($oUser) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function fetchUserById($id)
    {
        return self::find($id);
    }

    public function updateUserById($id, array $data)
    {
        $user = self::find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }



    public static function fetchAllUser()
    {
        try {
            $oUser = self::select('*')
            ->where('status',1)
            ->where('deleted', 0)
                ->get();


            return $oUser;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function freezeUnFreeze($iId)
    {
        try {
            // Find the user by id
            $oUser = self::find($iId);

            if (!$oUser) {
                throw new Exception('User not found');
            }

            // Toggle the status between 0 (frozen) and 1 (active)
            $oUser->status = $oUser->status == 1 ? 0 : 1;
            $oUser->save();

            return $oUser;
        } catch (Exception $e) {
            throw $e;
        }
    }


}
