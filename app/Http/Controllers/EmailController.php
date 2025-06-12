<?php

namespace App\Http\Controllers;

use App\Mail\OTP;
use App\Models\WellnessOtp;
use App\Service\BasicOpration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    public function generateMail(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'user_email_id' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $userEmail = $request->input('user_email_id');

            $oCheckOTP = (new WellnessOtp())->fetchActiveOTP($userEmail);

            if ((isset($oCheckOTP))) {
                throw new Exception('Already otp send');
            }

            // Generate OTP Randomly
            $otp = (new BasicOpration())->genrateRandomNummber();

            $details = [
                'title' => 'Kvitas Healling Reanaissance',
                'otp' => $otp,
                'contact_details' => 'For more information, contact us at healling.reanaissance@lifehealerKvita.com or call (123) 456-7890.',
            ];

            // Send the email
            $oResult = Mail::to($userEmail)->send(new OTP($details));
            if ($oResult) {
                $resultData = (new WellnessOtp())->genrateOTP($userEmail, $otp);
                if (!$resultData) {
                    throw new Exception('Failed to generate OTP entry in the database.');
                }
            }else{
                throw new Exception('Failed to Send OTP.');
            }

            return response()->json(['message' => 'Email sent successfully!', 'body' => null, 'status' => 200], 200);

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
