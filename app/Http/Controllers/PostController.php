<?php

namespace App\Http\Controllers;

use App\Http\Requests\post\StorePostRequest;
use App\Http\Requests\post\UpdatePostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $posts = Post::where('user_id', '=', $user->id)->get();
            return response()->json([
                'message' => 'success',
                'posts' => $posts
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $post = Post::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'content' => $request->content
            ]);
            return response()->json([
                'message' => 'post created successfully',
                'post' => $post
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        try {
            $user = Auth::user();
            if ($user->id == $post->user_id) {
                return response()->json([
                    'message' => 'success',
                    'post' => $post
                ], 200);
            }
            return response()->json(['message' => 'You do not have permission to view this post'], 403);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            $user = Auth::user();
            if ($user->id == $post->user_id) {
                // update only the fields that are present in the request
                $post->update($request->validated());
               
                return response()->json([
                    'message' => 'post updated successfully',
                    'post' => $post
                ], 200);
            }
            return response()->json(['message' => 'You do not have permission to update this post'], 403);
        }
        catch (ModelNotFoundException $e) {
            // Handle the case where the post is not found
            return response()->json([
                'message' => 'Post not found',
                'error' => $e->getMessage(),
            ], 404);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);   
                    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            $user = Auth::user();
            if ($user->id == $post->user_id) {
                $post->delete();
                return response()->json([
                    'message' => 'post deleted successfully',
                    'post' => $post
                ], 200);
            }
            return response()->json(['message' => 'You do not have permission to delete this post'], 403);
        }
        catch (ModelNotFoundException $e) {
            // Handle the case where the post is not found
            return response()->json([
                'message' => 'Post not found',
                'error' => $e->getMessage(),
            ], 404);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }
}
