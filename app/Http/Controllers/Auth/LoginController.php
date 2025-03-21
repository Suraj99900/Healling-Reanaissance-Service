<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request...

        // Attempt to log the user in...
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $sessionManager = new \App\Models\SessionManager();
            $sessionManager->fSetSessionData([
                'iUserID' => $user->id,
                'sUserMobileNo' => $user->mobile_no,
                'sUserName' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'isLoggedIn' => true,
            ]);

            return response()->json(['message' => 'Login successful', 'body' => $user]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return response()->json(['message' => 'Logged out successfully']);
    }
}