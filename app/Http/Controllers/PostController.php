<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //Get all posts
    public function index()
    {
        return Post::all();
    }

    //Add a new post
    public function store(Request $request)
    {
        try {
            $post = new Post();
            $post->title = $request->title;
            $post->body = $request->body;

            if ($post->save()) {
                return response()->json(['status' => 'success', 'message' => 'Post saved!', 'data' => $post]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => null]);
        }
    }

    //Update a post
    public function update(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->title = $request->title;
            $post->body = $request->body;

            if ($post->save()) {
                return response()->json(['status' => 'success', 'message' => 'Post updated!', 'data' => $post]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'data' => null]);
        }
    }

    //Delete a post
    public function destroy(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post->delete()) {
                return response()->json(['status' => 'success', 'message' => 'Post deleted!', 'data' => $post]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'data' => null]);
        }
    }

    //Find a single post
    public function single(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post) {
                return response()->json(['status' => 'success', 'message' => 'Post found!', 'data' => $post]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'data' => null]);
        }
    }
}
