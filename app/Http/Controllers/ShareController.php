<?php

namespace App\Http\Controllers;

use App\Models\Share;
use App\Models\Post;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    // Share a post
    public function store(Request $request, $postId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to share this post.');
        }

        $post = Post::findOrFail($postId);

        // Create the share
        $share = Share::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
        ]);

        // Redirect back to the post page with a success message
        return redirect()->route('posts.show', $post->id)->with('message', 'Post shared successfully!');
    }
}
