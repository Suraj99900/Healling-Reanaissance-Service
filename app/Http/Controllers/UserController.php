<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WellnessOtp;
use App\Service\BasicOpration;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function addUser(Request $request)
    {
        try {
            $oValidator = Validator::make($request->all(), [
                'userName' => 'required',
                'email' => 'required',
                'secretkey' => 'required',
                'password' => 'required',
                'userType' => 'required',
                'otp' => 'required',
            ]);
            if ($oValidator->fails()) {
                return response()->json(['error' => $oValidator->errors()], 400);
            }

            $sUserName = $request->input('userName');
            $sEmail = $request->input('email');
            $sSecretkey = $request->input('secretkey');
            $sPassword = $request->input('password');
            $iUserType = $request->input('userType');
            $sOTP = $request->input('otp');
            $sType = '';

            // check otp valid or not 
            $oCheckOTP = (new WellnessOtp())->fetchActiveOTP($sEmail);

            // Checlk key is valid or not 
            if (((new User())->checkUserPresent($sEmail))) {
                throw new Exception('User already present.');
            }

            // check user already present or not 
            if (!((new BasicOpration())->checkSecretkey($sSecretkey))) {
                throw new Exception('Secret key not valid.');
            }

            if (!(isset($oCheckOTP))) {
                throw new Exception('OTP Expired');
            } else {
                (new WellnessOtp())->validateOTP($sOTP);
            }

            // fetch the userType...
            $sType = (new BasicOpration())->fetchUserType($iUserType);
            // Convert the Normal TextPassword in HashPassword
            $sPassword = (new BasicOpration())->convertPasswordToHash($sPassword);
            // insert the user data in table ...
            $oUserResult = (new User())->addUser($sUserName, $sEmail, $sPassword, $iUserType, $sType);

            if ($oUserResult) {
                return response()->json([
                    'message' => "User Added successfully !",
                    'body' => $oUserResult,
                    'status' => 200,
                ], 200);
            }
        } catch (Exception $e) {
            // Log the error for further analysis
            Log::error('Error in sending email: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    function login(Request $request)
    {
        try {
            $oValidator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);
            if ($oValidator->fails()) {
                return response()->json(['error' => $oValidator->errors()], 400);
            }

            $sEmail = $request->input('email');
            $sPassword = $request->input('password');

            $oUserResult = (new User())->fetchUserByEmail($sEmail);
            if (!isset($oUserResult)) {
                throw new Exception("user not found.");
            }

            if (password_verify($sPassword, $oUserResult->password)) {
                return response()->json([
                    'message' => "Valid User.",
                    'body' => $oUserResult,
                    'status' => 200,
                ], 200);
            } else {
                return response()->json([
                    'message' => "Incorect Password",
                    'status' => 500,
                ], 500);
            }
        } catch (Exception $e) {
            // Log the error for further analysis
            Log::error('Error in sending email: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {

            $oValidator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
                'otp' => 'required',
            ]);
            if ($oValidator->fails()) {
                return response()->json(['error' => $oValidator->errors()], 400);
            }

            $sEmail = $request->input('email');
            $sNewPassword = $request->input('newPassword');
            $sOTP = $request->input('otp');

            // check otp valid or not 
            $oCheckOTP = (new WellnessOtp())->fetchActiveOTP($sEmail);

            // Checlk key is valid or not 
            if (((new User())->checkUserPresent($sEmail))) {
                throw new Exception('User already present.');
            }

            if (!(isset($oCheckOTP))) {
                throw new Exception('OTP Expired');
            } else {
                (new WellnessOtp())->validateOTP($sOTP);
            }

            // Convert the Normal TextPassword in HashPassword
            $sNewPassword = (new BasicOpration())->convertPasswordToHash($sNewPassword);

            //! update user Password
            $oResult = (new User());

        } catch (Exception $e) {
            // Log the error for further analysis
            Log::error('Error in sending email: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
