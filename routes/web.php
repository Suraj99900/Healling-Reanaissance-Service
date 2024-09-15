<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api', 'middleware' => 'ClientAuth'], function () use ($router) {
    // User Register and Login
    $router->get('login', 'UserController@login');
    $router->post('users', 'UserController@addUser');
    $router->put('password', 'UserController@updatePassword');
    $router->put('users/{id}', 'UserController@update');
    $router->delete('users/{id}', 'UserController@destroy');

    // Email route
    $router->post('email', 'EmailController@generateMail');

    // Video upload
    $router->post('video', 'VideoController@upload');
    $router->get('video/{id}', 'VideoController@fetchById');
    $router->get('videos', 'VideoController@fetchAll');
    $router->get('videos-category/{id}', 'VideoController@fetchAllVideoDataByCategoryId');
    $router->get('videos/paginated', 'VideoController@fetchAllWithPagination');
    $router->get('videos/search', 'VideoController@searchByTitle');
    $router->put('video/{id}', 'VideoController@update');
    $router->delete('video/{id}', 'VideoController@destroy');
    $router->get('stream/{id}', 'VideoController@stream');
    $router->get('thumbnail/{id}', 'VideoController@thumbnailImages');

    // Video Category
    $router->post('video-category', 'VideoCategoryController@addCategory');
    $router->get('video-categories', 'VideoCategoryController@getAllCategories');
    $router->get('video-category/{id}', 'VideoCategoryController@getCategoryById');
    $router->put('video-category/{id}', 'VideoCategoryController@updateCategory');
    $router->delete('video-category/{id}', 'VideoCategoryController@deleteCategory');

    // Attachment route
    $router->post('app-attachment', 'AttachmentFileController@addAttchmentData');
    $router->get('video/app-attachment/{id}', 'AttachmentFileController@fetchAllAttachmentDataByVideoId');
    $router->delete('app-attachment/{id}', 'AttachmentFileController@removedAttchment');

    // Video Comment 
    $router->post('video/comment', 'UserCommentController@addUserComment');
    $router->put('video/comment', 'UserCommentController@updateUserComment');
    $router->get('video/comment/{id}', 'UserCommentController@fetchUserCommentByVideoId');
    $router->get('video/comment', 'UserCommentController@fetchAllUserComment');
    $router->delete('video/comment/{id}', 'UserCommentController@invalidUserCommentById');

    // Blog post API
    $router->post('blog', 'AppPostController@store');
    $router->put('blog/{id}', 'AppPostController@update');
    $router->get('blog', 'AppPostController@index');
    $router->get('blog/{id}', 'AppPostController@show');
    $router->delete('blog', 'AppPostController@destroy');
});
