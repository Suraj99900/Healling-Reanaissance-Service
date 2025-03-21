<?php

namespace App\Models;

use Illuminate\Support\Facades\Session;

class SessionManager
{
    public $iUserID;
    public $sUserMobileNo;
    public $sUserName;
    public $sEmail;
    public $iUserType;
    public $isLoggedIn;

    public function __construct()
    {
        // Initialize session variables
        if (Session::has('isLoggedIn')) {
            $this->iUserID = Session::get('iUserID');
            $this->sEmail = Session::get('email');
            $this->sUserName = Session::get('sUserName');
            $this->iUserType = Session::get('user_type');
            $this->isLoggedIn = Session::get('isLoggedIn');
            // print_r($this->sEmail);die;
        }
    }

    /**
     * Set session data.
     */
    public function fSetSessionData($aSessionData)
    {
        
        // Set session data
        Session::put('iUserID', $aSessionData['iUserID']);
        Session::put('sUserName', $aSessionData['sUserName']);
        Session::put('email', $aSessionData['email']);
        Session::put('isLoggedIn', $aSessionData['isLoggedIn']);
        Session::put('user_type', $aSessionData['user_type']);
        Session::save();
        

        // Update properties
        $this->iUserID = $aSessionData['iUserID'];
        $this->sUserName = $aSessionData['sUserName'];
        $this->sEmail = $aSessionData['email'];
        $this->isLoggedIn = $aSessionData['isLoggedIn'];
        $this->iUserType = $aSessionData['user_type'];

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
