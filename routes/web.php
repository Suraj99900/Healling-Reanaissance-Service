<?php

use App\Http\Controllers\SessionManagerController;
use App\Http\Controllers\UserCategoryAccessController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoCategoryController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\SessionManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', action: function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});


Route::get('dashboard', function () {
    $sessionData = Session::all(); // Retrieve all session data

    if ((new SessionManager())->isLoggedIn()) {
        return view('dashboard')->with('sessionData', $sessionData);
    } else {
        return view('login');
    }
});

Route::get('/forgotPasswordScreen', function () {
    return view('forgotPasswordScreen');
});

Route::get('/user-access', [UserCategoryAccessController::class, 'index'])->name('access.management');

Route::get('/user-management', [UserController::class, 'index'])->name('user.management');

Route::get('/video-management', [VideoController::class, 'index'])->name('video.management');

Route::get('/category-management', [VideoCategoryController::class, 'index'])->name('category.management');

Route::get('home', function () {
    $sessionData = Session::all(); // Retrieve all session data

    if ((new SessionManager())->isLoggedIn()) {
        return view('home')->with('sessionData', $sessionData);
    } else {
        return view('login');
    }
});


Route::post('setSession', [SessionManagerController::class, 'setUserSession']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('logOutSession', [SessionManagerController::class, 'destroySession']);

Route::get('videos/{categoryId?}', function () {
    $sessionManager = new \App\Models\SessionManager();
    $categoryId = request()->route('categoryId');
    $userType = $sessionManager->iUserType;

    return view('video-list', compact('categoryId', 'userType'));
});


Route::get('videos/videos-player/{videoId}', function () {
    $videoId = request()->route('videoId');

    return view('video-player', compact('videoId'));
});