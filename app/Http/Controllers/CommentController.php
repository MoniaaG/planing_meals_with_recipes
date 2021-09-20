<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function show(Comment $comment) {
        return $comment;
    }

    public function index() {
        return Comment::all();
    }

    public function create(Request $request) {
        $comment = new Comment();
        $comment->creator_id = Auth::id();
        $comment->recipe_id = $request->recipe_id;
        $comment->content = $request->content;
        //is_blocked for admin Controller
        $comment->save();
    }

    public function edit(Comment $comment) {
        return $comment;
    }

    public function update(Comment $comment, Request $request) {
        $comment = Comment::findOrFail($comment->id);
        $comment->creator_id = Auth::id();
        $comment->recipe_id = $request->recipe_id;
        $comment->content = $request->content;
        $comment->save();
    }

    public function delete(Comment $comment, Request $request) {
        $comment->delete();
    }
}
