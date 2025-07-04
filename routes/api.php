<?php

use App\Http\Controllers\EnrollmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppPostController;
use App\Http\Controllers\AttachmentFileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoCategoryController;
use App\Http\Controllers\UserCategoryAccessController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// User Register and Login
Route::get('login', [UserController::class, 'login']);
Route::post('users', [UserController::class, 'addUser']);
Route::put('password', [UserController::class, 'updatePassword']);
Route::post('users/direct', [UserController::class, 'addUserWithoutOTP']);
Route::put('users/{id}', [UserController::class, 'updateUserById']);
Route::delete('users/{id}', [UserController::class, 'FreezeUnFreeze']);
// get All users
Route::get('users', [UserController::class, 'fetchAllUser']);
Route::get('users/{id}', [UserController::class, 'fetchUserById']);


// Email route
Route::post('email', [EmailController::class, 'generateMail']);

// Video upload
Route::post('video', [VideoController::class, 'upload']);
Route::post('uploadChunk', [VideoController::class, 'uploadChunk']);
Route::get('video/{id}', [VideoController::class, 'fetchById']);
Route::get('videos', [VideoController::class, 'fetchAll']);
Route::get('videos-category/{id}', [VideoController::class, 'fetchAllVideoDataByCategoryId']);
Route::get('videos/paginated', [VideoController::class, 'fetchAllWithPagination']);
Route::get('videos/search', [VideoController::class, 'searchByTitle']);
Route::put('video/{id}', [VideoController::class, 'update']);
Route::delete('video/{id}', [VideoController::class, 'destroy']);
Route::get('stream/{id}', [VideoController::class, 'stream']);
Route::get('thumbnail/{id}', [VideoController::class, 'thumbnailImages']);

// Video Category
Route::post('video-category', [VideoCategoryController::class, 'addCategory']);
Route::get('video-categories', [VideoCategoryController::class, 'getAllCategories']);
Route::get('video-categories/{userId}/user', [VideoCategoryController::class, 'getUserCategoryAccess']);
Route::get('video-category/{id}', [VideoCategoryController::class, 'getCategoryById']);
Route::put('video-category/{id}', [VideoCategoryController::class, 'updateCategory']);
Route::delete('video-category/{id}', [VideoCategoryController::class, 'deleteCategory']);

// Attachment route
Route::post('app-attachment', [AttachmentFileController::class, 'addAttchmentData']);
Route::get('video/app-attachment/{id}', [AttachmentFileController::class, 'fetchAllAttachmentDataByVideoId']);
Route::delete('app-attachment/{id}', [AttachmentFileController::class, 'removedAttchment']);

// Video Comment 
Route::post('video/comment', [UserCommentController::class, 'addUserComment']);
Route::put('video/comment', [UserCommentController::class, 'updateUserComment']);
Route::get('video/comment/{id}', [UserCommentController::class, 'fetchUserCommentByVideoId']);
Route::get('video/comment', [UserCommentController::class, 'fetchAllUserComment']);
Route::delete('video/comment/{id}', [UserCommentController::class, 'invalidUserCommentById']);

// Blog post API
Route::post('blog', [AppPostController::class, 'store']);
Route::put('blog/{id}', [AppPostController::class, 'update']);
Route::get('blog', [AppPostController::class, 'index']);
Route::get('blog/{id}', [AppPostController::class, 'show']);
Route::delete('blog', [AppPostController::class, 'destroy']);
// Access API
Route::get('/user-category-access', [UserCategoryAccessController::class, 'getUsersWithCategoryAccess']);
Route::post('/user-category-access', [UserCategoryAccessController::class, 'grantAccess']);
Route::put('/user-category-access/{id}', [UserCategoryAccessController::class, 'updateAccess']);
Route::delete('/user-category-access/{id}', [UserCategoryAccessController::class, 'deleteAccess']);

Route::get('/test-s3-connection', function () {
    try {
        $files = Storage::disk('spaces')->files('/');
        dd('✅ Connection successful!', $files);
    } catch (\Exception $e) {
        dd('❌ Connection failed:', $e->getMessage());
    }
});


Route::get('/enrollments', [EnrollmentController::class, 'listEnrollments'])->name('enrollment.list'); // List all
Route::get('/enrollments/{id}', [EnrollmentController::class, 'showEnrollment'])->name('enrollment.show'); // View single

Route::get('/enrollments/{id}/edit', [EnrollmentController::class, 'editEnrollment'])->name('enrollment.edit'); // Edit form
Route::post('/enrollments/{id}/update', [EnrollmentController::class, 'updateEnrollment'])->name('enrollment.update'); // Update

Route::delete('/enrollments/{id}', [EnrollmentController::class, 'deleteEnrollment'])->name('enrollment.delete'); // Delete
