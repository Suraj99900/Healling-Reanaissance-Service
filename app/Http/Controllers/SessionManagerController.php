<?php

namespace App\Http\Controllers;

use App\Models\SessionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionManagerController extends Controller
{
    /**
     * Handles user login.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUserSession(Request $request)
    {
        $request->validate([
            'id'=>'required',
            'username'=>'required',
            'user_type'=>'required',
            'email'=>'required',
            'login'=>'required'
        ]);
        $aSessionData = array();
        $aSessionData['iUserID'] = $request->input('id','');
        $aSessionData['sUserName'] = $request->input('username','');
        $aSessionData['user_type'] = $request->input('user_type','');
        $aSessionData['email'] = $request->input('email','');
        $aSessionData['isLoggedIn'] = $request->input('login','');

        if ($aSessionData['isLoggedIn'] == 1 ) {
            $oSessionManager = new SessionManager();
            $oSessionManager->fSetSessionData($aSessionData);
            return response()->json(['success' => true,'aSessionData' => session::all(),'message'=>'ok']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid login credentials']);
    }

    /**
     * Sets user session data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setSessionData(Request $request)
    {
        $data = $request->all();

        // Store all session data
        foreach ($data as $key => $value) {
            Session::put($key, $value);
        }

        return response()->json(['message' => 'Session data stored successfully', 'data' => $data]);
    }

    /**
     * Sets admin session data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAdminSessionData(Request $request)
    {
        $data = $request->all();

        // Store admin-specific session data
        foreach ($data as $key => $value) {
            Session::put($key, $value);
        }

        return response()->json(['message' => 'Admin session data stored successfully', 'data' => $data]);
    }

    public function destroySession(Request $request){
        $oSessionObject = new SessionManager();
        $oSessionObject->destroySession();
        return response()->json(['success' => true,'message'=>'ok']);
    }
}
