<!-- /views/posts/show.blade.php -->


@extends('layouts.app')

@section('content')
    <h1>{{ $post->caption }}</h1>
    <p>Type: {{ ucfirst($post->type) }}</p>

    @if (auth()->check() && (auth()->id() == $post->user_id || auth()->user()->role == 'admin'))
        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-secondary">Edit Post</a>
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Post</button>
        </form>
    @endif

    <form action="{{ route('likes.toggle', $post->id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit"
            class="btn {{ auth()->check() && $post->likes->contains('user_id', auth()->id()) ? 'btn-danger' : 'btn-primary' }}">
            {{ auth()->check() && $post->likes->contains('user_id', auth()->id()) ? 'Unlike' : 'Like' }}
        </button>
    </form>


    <p>Likes: {{ $post->likes->count() }}</p>

    <div class="dropdown my-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-paper-plane"></i> Share
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item"
                    href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                    target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a></li>
            <li><a class="dropdown-item" href="https://twitter.com/share?url={{ urlencode(request()->fullUrl()) }}"
                    target="_blank"><i class="fab fa-twitter"></i> Twitter</a></li>
            <li><a class="dropdown-item" href="https://wa.me/?text={{ urlencode(request()->fullUrl()) }}"
                    target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
        </ul>
    </div>

    <hr>

    <h2>Comments</h2>
    @foreach ($post->comments as $comment)
        <div class="comment border p-2 my-2 rounded">
            <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}</p>

            <form action="{{ route('comments.likes.toggle', $comment->id) }}" method="POST" class="d-inline">
                @csrf
                @auth
                    <button type="submit"
                        class="btn btn-sm {{ $comment->likes->contains('user_id', auth()->id()) ? 'btn-danger' : 'btn-outline-primary' }}">
                        {{ $comment->likes->contains('user_id', auth()->id()) ? 'Unlike' : 'Like' }}
                    </button>
                @else
                    <button type="submit" class="btn btn-sm btn-outline-primary"
                        onclick="return confirm('Please login to like this comment.')">
                        Like
                    </button>
                @endauth
            </form>
            <span class="ms-1">Likes: {{ $comment->likes->count() }}</span>
        </div>
    @endforeach


    <form action="{{ route('comments.store', $post->id) }}" method="POST">
        @csrf
        <textarea name="content" class="form-control" placeholder="Add a comment" required></textarea>
        <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
    </form>
@endsection
