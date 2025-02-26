<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCategoryAccess;
use Illuminate\Support\Facades\Validator;

class UserCategoryAccessController extends Controller
{
    /**
     * Fetch users who have access to a given category.
     */
    public function getUsersWithCategoryAccess(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'user_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'status' => 'nullable|integer|in:0,1',
        ]);

        if ($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 422);
        }

        $userId = $request->input('user_id');
        $categoryId = $request->input('category_id');
        $status = $request->input('status', 1);

        $users = UserCategoryAccess::getUsersWithCategoryDetails($userId, $categoryId, $status);

        return response()->json(['data' => $users], 200);
    }

    /**
     * Grant access to a user for a specific category.
     */
    public function grantAccess(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:wellness_users,id',
            'category_id' => 'required|integer|exists:video_category,id',
            'expiration_time' => 'required|date|after:now',
        ]);

        if ($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 422);
        }

        $access = UserCategoryAccess::create([
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'access_time' => now(),
            'expiration_time' => $request->expiration_time,
            'added_on' => now(),
            'status' => 1,
            'deleted' => 0,
        ]);

        return response()->json(['message' => 'Access granted successfully', 'data' => $access], 201);
    }

    /**
     * Update access for a user and category.
     */
    public function updateAccess(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'expiration_time' => 'required|date|after:now',
            'status' => 'nullable|integer|in:0,1',
        ]);

        if ($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 422);
        }

        $access = UserCategoryAccess::find($id);
        if (!$access) {
            return response()->json(['error' => 'Access record not found'], 404);
        }

        $access->update([
            'expiration_time' => $request->expiration_time,
            'status' => $request->status ?? $access->status,
        ]);

        return response()->json(['message' => 'Access updated successfully', 'data' => $access], 200);
    }

    /**
     * Remove access (soft delete).
     */
    public function deleteAccess($id)
    {
        $access = UserCategoryAccess::find($id);
        if (!$access) {
            return response()->json(['error' => 'Access record not found'], 404);
        }

        $access->update(['deleted' => 1]);

        return response()->json(['message' => 'Access revoked successfully'], 200);
    }
}
