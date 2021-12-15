<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // All posts
    public function getAllPosts() {
        $posts = Post::all();
        foreach($posts as $post) {
            $post->comments;
            $post->user;
            foreach($post->comments as $comment) {
                $comment->user;
            }
        }

        return response()->json([
            'posts' => $posts,
        ], 200);
    }

    // Posts for specific user
    public function getMyPosts(User $user) {
        $posts = $user->posts;
        foreach($posts as $post) {
            $post->comments;
            $post->user;
            foreach($post->comments as $comment) {
                $comment->user;
            }
        }

        return response()->json([
            'posts' => $posts,
        ], 200);
    }

    // Create post
    public function createPost(Request $request) {

        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = $request->user_id;
        if($post->save()) {
            $post->user;
            $post->comments;
            return response()->json([
                'post' => $post
            ], 201);
        } 
        else {
            return response()->json([
                'errors' => [
                    'other' => 'ERROR: Could not create post!'
                ]
            ], 500);
        }
    }

    // Delete post
    public function deletePost(User $user, Post $post) {
        if($user->role->name == 'admin' || $post->user_id == $user->id) {
            foreach($post->comments as $comment) {
                $comment->delete();
            }
            if($post->delete()) {
                return response()->json([
                    'message' => 'Successfully deleted post!',
                ], 200);
            }
            else {
                return response()->json([
                    'errors' => [
                        'other' => 'ERROR: Could not delete post!',
                    ]
                ], 500);
            }
        }
        else {
            return response()->json([
                'errors' => [
                    'other' => 'ERROR: You cannot delete this post!',
                ]
            ], 401);
        }
    }
}
