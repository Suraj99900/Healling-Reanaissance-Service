<?php
namespace App\Service;
class BasicOpration
{

    public static function genrateRandomNummber(){
        $sOTP = '';
        for ($iii=0; $iii < 4 ; $iii++) { 
            $sOTP .= rand(0,9);
        }
        return $sOTP; //Genrate random number...
    }

    public static function fetchUserType($iType){
        $sUserType = '';
        if (isset($iType)) {
            switch ($iType) {
                case 1:
                    $sUserType = 'Supper-Admin';
                    break;
                case 2:
                    $sUserType = 'App-user';
                    break;
                case 3:
                    $sUserType = 'Developer';
                    break;
                case 4:
                    $sUserType = 'Admin';
                    break;
                default:
                    break;
            }
        }
        return $sUserType;
    }


    public static function convertPasswordToHash($sPassword){
        $sNewPassword = password_hash($sPassword,PASSWORD_BCRYPT);
        return $sNewPassword;
    }

    public static function checkSecretkey($sKey){
        $oResult = (new Client())->getClientByClientID($sKey);
        if (isset($oResult)) {
            return true;
        }else{
            return false;
        }
    }
    
}
