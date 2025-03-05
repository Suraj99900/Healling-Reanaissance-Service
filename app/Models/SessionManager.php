<?php

namespace App\Models;

use Illuminate\Support\Facades\Session;

class SessionManager
{
    public $iUserID;
    public $sUserMobileNo;
    public $sUserName;
    public $isLoggedIn;

    public function __construct()
    {
        // Initialize session variables
        if (Session::has('isLoggedIn')) {
            $this->iUserID = Session::get('iUserID');
            $this->sUserMobileNo = Session::get('sUserMobileNo');
            $this->sUserName = Session::get('sUserName');
            $this->isLoggedIn = Session::get('isLoggedIn');
        }
    }

    /**
     * Set session data.
     */
    public function fSetSessionData($aSessionData)
    {
        // Set session data
        Session::put('iUserID', $aSessionData['id']);
        Session::put('sUserName', $aSessionData['username']);
        Session::put('sUserMobileNo', $aSessionData['phoneNumber']);
        Session::put('isLoggedIn', $aSessionData['login']);

        // Update properties
        $this->iUserID = $aSessionData['id'];
        $this->sUserMobileNo = $aSessionData['phoneNumber'];
        $this->sUserName = $aSessionData['username'];
        $this->isLoggedIn = $aSessionData['login'];
    }

    /**
     * Check if the user is logged in.
     */
    public function isLoggedIn()
    {
        return Session::get('isLoggedIn', false);
    }

    /**
     * Destroy the session.
     */
    public function destroySession()
    {
        Session::flush();
    }
}
