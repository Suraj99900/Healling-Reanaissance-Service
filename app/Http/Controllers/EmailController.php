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

            if((isset($oCheckOTP))){
                throw new Exception('Already otp send');
            }

            // Generate OTP Randomly
            $otp = (new BasicOpration())->genrateRandomNummber();

            $details = [
                'title' => 'WELLNESS SERVICE',
                'otp' => $otp,
                'contact_details' => 'For more information, contact us at support@wellnessservice.com or call (123) 456-7890.',
            ];

            $resultData = (new WellnessOtp())->genrateOTP($userEmail, $otp);
            if (!$resultData) {
                throw new Exception('Failed to generate OTP entry in the database.');
            }

            // Send the email
            Mail::to($userEmail)->send(new OTP($details));

            return response()->json(['message' => 'Email sent successfully!','body' => true,,'status'=>200], 200);

        } catch (Exception $e) {
            // Log the error for further analysis
            Log::error('Error in sending email: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'body' => false,
                'message' => $e->getMessage(),
                'status'=> 500
            ], 500);
        }
    }
}
