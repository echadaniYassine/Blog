<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggleLike($postId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to like this post.');
        }
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        // Check if user has already liked the post
        $existingLike = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();

        if ($existingLike) {
            $existingLike->delete(); // Unlike the post
            return redirect()->back()->with('message', 'Like removed successfully!');
        } else {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            return redirect()->back()->with('message', 'Post liked successfully!');
        }
    }
}

