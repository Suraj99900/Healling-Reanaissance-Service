<?php

namespace App\Http\Controllers;

use App\Models\AppPost;
use Illuminate\Http\Request;

class AppPostController extends Controller
{
    public function index()
    {
        return response()->json(AppPost::fetchAllPosts());
    }

    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $post = AppPost::addPost($request->body);

        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = AppPost::fetchPost($id);

        if ($post) {
            return response()->json($post);
        } else {
            return response()->json(['message' => 'Post not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $post = AppPost::updatePost($id, $request->body);

        if ($post) {
            return response()->json($post);
        } else {
            return response()->json(['message' => 'Post not found'], 404);
        }
    }

    public function destroy($id)
    {
        $post = AppPost::deletePost($id);

        if ($post) {
            return response()->json(['message' => 'Post deleted']);
        } else {
            return response()->json(['message' => 'Post not found'], 404);
        }
    }
}
