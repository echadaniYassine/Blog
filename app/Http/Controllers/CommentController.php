<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to comment on this post.');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $post = Post::findOrFail($postId);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('message', 'Comment added successfully!');
    }

    public function toggle($commentId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to like a comment.');
        }

        $comment = Comment::findOrFail($commentId);

        $existingLike = Like::where('user_id', auth()->id())->where('comment_id', $commentId)->first();

        if ($existingLike) {
            $existingLike->delete();
            return redirect()->back()->with('message', 'Comment unliked.');
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'comment_id' => $commentId,
            ]);
            return redirect()->back()->with('message', 'Comment liked.');
        }
    }


    public function destroy(Comment $comment)
    {
        // Optional: check if the authenticated user is allowed to delete
        if (auth()->id() !== $comment->user_id && auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $comment->delete();

        return redirect()->back()->with('message', 'Comment deleted successfully.');
    }



    public function reply(Request $request, $postId, $commentId)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $postId,
            'content' => $request->content,
            'parent_id' => $commentId
        ]);

        return back()->with('message', 'Reply added successfully!');
    }
}
