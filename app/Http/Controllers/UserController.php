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

    public function index()
    {
        return view('user_management'); // Create resources/views/video-management.blade.php
    }
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

    public function addUserWithoutOTP(Request $request)
    {
        try {
            $oValidator = Validator::make($request->all(), [
                'userName' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'userType' => 'required',
                // Note: OTP is not required in this function
            ]);
            if ($oValidator->fails()) {
                return response()->json(['error' => $oValidator->errors()], 400);
            }

            $sUserName = $request->input('userName');
            $sEmail = $request->input('email');
            $sPassword = $request->input('password');
            $iUserType = $request->input('userType');
            $sType = '';

            // Check if user already exists
            if ((new User())->checkUserPresent($sEmail)) {
                throw new Exception('User already present.');
            }

            // Fetch the user type text
            $sType = (new BasicOpration())->fetchUserType($iUserType);
            // Convert the plain text password to hashed password
            $sPassword = (new BasicOpration())->convertPasswordToHash($sPassword);
            // Insert user data into the database
            $oUserResult = (new User())->addUser($sUserName, $sEmail, $sPassword, $iUserType, $sType);

            if ($oUserResult) {
                return response()->json([
                    'message' => "User Added successfully!",
                    'body' => $oUserResult,
                    'status' => 200,
                ], 200);
            }
        } catch (Exception $e) {
            Log::error('Error in addUserWithoutOTP: ' . $e->getMessage());

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
            $sNewPassword = $request->input('password');
            $sOTP = $request->input('otp');

            // check otp valid or not 
            $oCheckOTP = (new WellnessOtp())->fetchActiveOTP($sEmail);

            // Checlk key is valid or not 
            if (!((new User())->checkUserPresent($sEmail))) {
                throw new Exception('User not present.');
            }

            if (!(isset($oCheckOTP))) {
                throw new Exception('OTP Expired');
            } else {
                (new WellnessOtp())->validateOTP($sOTP);
            }

            // Convert the Normal TextPassword in HashPassword
            $sNewPassword = (new BasicOpration())->convertPasswordToHash($sNewPassword);

            //! update user Password
            $oResult = (new User())->updateUserByEmailId($sEmail, $sNewPassword);

            if ($oResult) {
                return response()->json([
                    'message' => "Successfully updated",
                    'body' => $oResult,
                    'status' => 200,
                ], 200);
            } else {
                return response()->json([
                    'message' => "Error",
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

    public function fetchAllUser(Request $request)
    {
        try {
            $aUserData = (new User())->fetchAllUser();

            return response()->json([
                'message' => "Successfully ",
                'body' => $aUserData,
                'status' => 200,
            ], 200);

        } catch (Exception $e) {
            Log::error('Error in sending email: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }

    }

    public function FreezeUnFreeze($id)
    {
        try {
            $aUserData = (new User())->freezeUnFreeze($id);

            return response()->json([
                'message' => "Successfully ",
                'body' => $aUserData,
                'status' => 200,
            ], 200);

        } catch (Exception $e) {
            Log::error('Error in sending email: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function fetchUserById(Request $request, $id)
    {
        try {
            // Assuming you have a method in your User model that fetches a user by ID
            $oUser = (new User())->fetchUserById($id);

            if (!$oUser) {
                throw new Exception("User not found.");
            }

            return response()->json([
                'message' => "User fetched successfully.",
                'body' => $oUser,
                'status' => 200,
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in fetchUserById: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }


    public function updateUserById(Request $request, $id)
    {
        try {
            // Validate the incoming request fields.
            $oValidator = Validator::make($request->all(), [
                'userName' => 'required',
                'email' => 'required|email',
                'userType' => 'required',
                // 'password' is optional; if provided, we'll update it.
            ]);
            if ($oValidator->fails()) {
                return response()->json(['error' => $oValidator->errors()], 400);
            }

            $sUserName = $request->input('userName');
            $sEmail = $request->input('email');
            $iUserType = $request->input('userType');
            $sPassword = $request->input('password'); // Optional; update only if provided.

            // Fetch the textual representation of user type.
            $sType = (new BasicOpration())->fetchUserType($iUserType);

            // Retrieve the existing user record.
            $oUser = (new User())->fetchUserById($id);
            if (!$oUser) {
                throw new Exception("User not found.");
            }

            // Prepare the data array for update.
            $updateData = [
                'user_name' => $sUserName,
                'email' => $sEmail,
                'user_type' => $iUserType,
                'type' => $sType,
            ];

            // If a new password is provided, convert it to a hash and include it.
            if ($sPassword) {
                $updateData['password'] = (new BasicOpration())->convertPasswordToHash($sPassword);
            }

            // Update the user data.
            // Assumes your User model has an updateUserById method. Alternatively, if $oUser is an Eloquent model, you could call:
            // $oUser->update($updateData);
            $oUserResult = (new User())->updateUserById($id, $updateData);

            if ($oUserResult) {
                return response()->json([
                    'message' => "User updated successfully!",
                    'body' => $oUserResult,
                    'status' => 200,
                ], 200);
            } else {
                throw new Exception("User update failed.");
            }
        } catch (Exception $e) {
            Log::error('Error in updateUserById: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }



}
