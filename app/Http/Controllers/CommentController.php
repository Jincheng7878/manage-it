<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Scenario;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Scenario $scenario)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        Comment::create([
            'scenario_id' => $scenario->id,
            'user_id'     => auth()->id(),
            'content'     => $request->content,
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function destroy(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
