<?php

namespace App\Http\Controllers;

use App\Models\userComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserCommentController extends Controller
{
    public function addUserComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video_id' => 'required',
            'user_id' => 'required',
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $oComment = (new userComment())->addUserComment($request->input('video_id'), $request->input('user_id'), $request->input('comment'));
            if (!$oComment) {
                throw new \Exception('Failed to add userComment.');
            }
            return response()->json(['message' => 'successful', 'body' => $oComment, 'status' => 201], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function updateUserComment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $oComment = userComment::updateUserComment($request->input('comment'), $id);
            if (!$oComment) {
                throw new \Exception('Failed to update user comment.');
            }
            return response()->json(['message' => 'successful', 'body' => $oComment, 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Fetch user comments by video ID.
     */
    public function fetchUserCommentByVideoId($videoId)
    {
        try {
            $oComments = userComment::fetchUserCommentByVideoId($videoId);
            return response()->json(['message' => 'successful', 'body' => $oComments, 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Mark user comment as deleted.
     */
    public function invalidUserCommentById($id)
    {
        try {
            $oComment = userComment::invalidUserCommentById($id);
            if (!$oComment) {
                throw new \Exception('Failed to mark user comment as deleted.');
            }
            return response()->json(['message' => 'successful', 'body' => $oComment, 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Fetch all user comments.
     */
    public function fetchAllUserComment()
    {
        try {
            $oComments = userComment::fetchAllUserComment();
            return response()->json(['message' => 'successful', 'body' => $oComments, 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
