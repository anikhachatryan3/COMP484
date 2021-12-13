<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Create comment
    public function createComment(Request $request) {
        $request->validate([
            'body' => 'required',
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $comment = new Comment();
        $comment->body = $request->body;
        $comment->post_id = $request->post_id;
        $comment->user_id = $request->user_id;
        if($comment->save()) {
            return response()->json([
                'comment' => $comment
            ], 201);
        } 
        else {
            return response()->json([
                'errors' => [
                    'other' => 'ERROR: Could not create comment!'
                ]
            ], 500);
        }
    }

    // Delete comment
    public function deleteComment(Comment $comment, User $user) {
        if($user->role->name == 'admin' || $comment->user_id == $user->id) {
            if($comment->delete()) {
                return response()->json([
                    'message' => 'Successfully deleted comment!'
                ], 200);
            }
            else {
                return response()->json([
                    'errors' => [
                        'other' => 'ERROR: Could not delete comment!'
                    ],
                ], 500);
            }
        }
        else {
            return response()->json([
                'errors' => [
                    'other' => 'ERROR: You cannot delete this comment!'
                ],
            ], 401);
        }
    }
}
