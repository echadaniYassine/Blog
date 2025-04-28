<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function home()
    {
        $news = Cache::remember(
            'news_posts',
            5,
            fn() =>
            Post::with(['user', 'images'])->where('type', 'news')->latest()->take(3)->get()
        );
        $books = Cache::remember(
            'book_posts',
            5,
            fn() =>
            Post::with(['user', 'images'])->where('type', 'book')->latest()->take(3)->get()
        );
        $coursPosts = Cache::remember(
            'cours',
            5,
            fn() =>
            Post::with(['user', 'images'])->where('type', 'cours')->latest()->take(3)->get()
        );

        return view('pages.home', compact('news', 'books', 'coursPosts'));
    }

    public function index(Request $request)
    {
        $type = $request->input('type', 'cours');
        $posts = Post::where('type', $type)->latest()->get();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $user = $post->user;
        return view('posts.show', compact('post', 'user'));
    }

    public function createPostBlog()
    {
        return view('posts.createPostBlog');
    }


    public function edit(Post $post)
    {
        if (auth()->id() !== $post->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('posts.edit', compact('post'));
    }

    public function store(Request $request)
    {
        $rules = [
            'caption' => 'required|string|max:255',
            'type' => 'required|in:news,book,cours',
        ];

        if ($request->type === 'news') {
            $rules['images'] = 'required|array';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } else {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        if ($request->type === 'book') {
            $rules['pdf'] = 'required|mimes:pdf|max:10240';
        }

        $request->validate($rules);

        $post = new Post();
        $post->user_id = auth()->id();
        $post->type = $request->type;
        $post->caption = $request->caption;
        $post->save();

        if ($request->type === 'news') {
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('posts', 'public');
                    $post->images()->create(['path' => $imagePath]);
                }
            }
        } else {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
                $post->images()->create(['path' => $imagePath]);
            }
        }

        if ($request->hasFile('pdf') && $request->type === 'book') {
            $pdfPath = $request->file('pdf')->store('books', 'public');
            $post->pdf()->create(['path' => $pdfPath]);
        }

        // Only clear the specific cache that was affected
        Cache::forget($request->type . '_posts');

        return redirect()->route('home')->with('message', 'Post created successfully.');
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'caption' => 'required|string|max:255',
            'type' => 'required|in:news,book,cours',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:10000',
        ]);

        // Store the original type to check if it changed
        $originalType = $post->type;

        $post->update([
            'type' => $request->type,
            'caption' => $request->caption,
        ]);

        if ($request->hasFile('image')) {
            // Only delete images if a new one is being uploaded
            $post->images->each(fn($image) => Storage::delete('public/' . $image->path));

            // Use consistent storage path
            $imagePath = $request->file('image')->store('posts', 'public');
            $post->images()->create(['path' => $imagePath]);
        }

        if ($request->hasFile('pdf') && $request->type === 'book') {
            // Delete old PDF if it exists
            if ($post->pdf) {
                Storage::delete('public/' . $post->pdf->path);
            }
            // Store new PDF
            $pdfPath = $request->file('pdf')->store('books', 'public');
            $post->pdf()->updateOrCreate([], ['path' => $pdfPath]);
        }

        // Clear caches more specifically
        if ($originalType !== $post->type) {
            Cache::forget($originalType . '_posts'); // Clear old type cache
        }
        Cache::forget($post->type . '_posts'); // Clear new type cache

        return redirect()->route('posts.index')->with('message', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Delete associated images and PDFs
        $post->images->each(fn($image) => Storage::delete('public/' . $image->path));
        if ($post->pdf) {
            Storage::delete('public/' . $post->pdf->path);
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

        if (Like::where('user_id', auth()->id())->where('post_id', $postId)->exists()) {
            return redirect()->back()->with('error', 'You have already liked this post.');
        }

        Like::create([
            'user_id' => auth()->id(),
            'post_id' => $postId,
        ]);

        return redirect()->back()->with('message', 'Post liked successfully!');
    }

    public function share($postId)
    {
        $post = Post::findOrFail($postId);
        $shareUrl = route('post.show', $post->id);
        return redirect()->away("https://www.facebook.com/sharer/sharer.php?u={$shareUrl}");
    }
}
