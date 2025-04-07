<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Import the Log facade

class PostController extends Controller
{
    /**
     * Show home page with latest categorized posts
     */
    public function home()
    {
        // You can optionally cache each section to improve performance
        $news = Cache::remember('news_posts', 60, function () {
            return Post::with('user')->where('type', 'news')->latest()->take(3)->get();
        });

        $books = Cache::remember('book_posts', 60, function () {
            return Post::with('user')->where('type', 'book')->latest()->take(3)->get();
        });

        $coursPosts = Cache::remember('cours', 60, function () {
            return Post::with('user')->where('type', 'cours')->latest()->take(3)->get();
        });

        return view('pages.home', compact('news', 'books', 'coursPosts'));
    }

    /**
     * Display a listing of posts filtered by type
     */
    public function index(Request $request)
    {
        $type = $request->input('type', 'cours'); // default to 'cours'
        $posts = Post::where('type', $type)->latest()->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Display a specific post
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        $user = $post->user; // Get the user (blogger) who created the post
        return view('posts.show', compact('post', 'user'));
    }

    /**
     * Show the form to create a new post
     */
    public function createPostBlog()
    {
        return view('posts.createPostBlog');
    }

    /**
     * Store a new post
     */

    public function store(Request $request)
    {
        // Validate the incoming request data
        $rules = [
            'caption' => 'required|string|max:255',
            'type' => 'required|in:news,book,cours',
            'images' => 'nullable|array', // Images should be an array
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image
        ];

        // Add PDF validation only for book type
        if ($request->type == 'book') {
            $rules['pdf'] = 'required|mimes:pdf|max:10240';
        }

        $request->validate($rules);

        // Create a new post object
        $post = new Post();
        $post->user_id = auth()->id();
        $post->type = $request->type;
        $post->caption = $request->caption;

        // Handle image upload (multiple images for news posts)
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                // Store each image and keep their paths in an array
                $images[] = $image->store('posts', 'public');
            }
            // Store the images array as JSON in the database
            $post->images = json_encode($images);
        }

        // Handle PDF upload for book posts
        if ($request->hasFile('pdf') && $request->type == 'book') {
            $post->pdf = $request->file('pdf')->store('books', 'public');
        }

        // Log for debugging
        Log::info('Creating post with data:', [
            'type' => $post->type,
            'caption' => $post->caption,
            'images' => $post->images ?? 'No images',
            'pdf' => $post->pdf ?? 'No PDF'
        ]);

        // Save the post to the database
        $post->save();

        // Redirect back with a success message
        return
            redirect()->route('home')->with('message', 'Post created successfully.');
    }

    /**
     * Show the form to edit a post
     */
    public function edit(Post $post)
    {
        // Optional: Check if the logged-in user is the owner
        if (auth()->id() !== $post->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update a post
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'caption' => 'required|string|max:255', // Assuming caption is required
            'type' => 'required|in:news,book,cours',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:10000',  // For book posts
        ]);

        $post = Post::findOrFail($id);

        $post->update([
            'type' => $request->type,
            'caption' => $request->caption,
        ]);

        // Handle image upload for normal and news posts
        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image) {
                Storage::delete('public/' . $post->image);
            }

            $imagePath = $request->file('image')->store('post_images', 'public');
            $post->image = $imagePath;
        }

        // Handle PDF upload for books
        if ($request->hasFile('pdf') && $request->type == 'book') {
            // Delete old PDF
            if ($post->pdf) {
                Storage::delete('public/' . $post->pdf);
            }

            $pdfPath = $request->file('pdf')->store('post_pdfs', 'public');
            $post->pdf = $pdfPath;
        }

        $post->save();

        return redirect()->route('posts.index')->with('message', 'Post updated successfully');
    }

    /**
     * Delete a post
     */
    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('message', 'Post deleted successfully!');
    }

    public function like($postId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to like this post.');
        }

        $post = Post::findOrFail($postId);

        // Prevent liking a post multiple times
        if (Like::where('user_id', auth()->id())->where('post_id', $postId)->exists()) {
            return redirect()->back()->with('error', 'You have already liked this post.');
        }

        Like::create([
            'user_id' => auth()->id(),
            'post_id' => $postId,
        ]);

        return redirect()->back()->with('message', 'Post liked successfully!');
    }

    // This is a simple placeholder method to simulate sharing a post
    public function share($postId)
    {
        $post = Post::findOrFail($postId);

        // Implement sharing logic here. For example, create a link to share on social media:
        $shareUrl = route('post.show', $post->id);
        return redirect()->away("https://www.facebook.com/sharer/sharer.php?u={$shareUrl}");
    }
}
