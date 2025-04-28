<!-- /views/posts/index.blade.php -->

@extends('layouts.app')
@section('content')
    <a href="{{ route('posts.createPostBlog') }}" class="btn btn-primary mb-3">Create Post</a>

    <div class="post-list">
        @foreach ($posts as $post)
            <div class="post-card">
                <div class="post-header">
                    <img src="{{ $post->user->profile_image }}" alt="Profile Image" class="profile-img">
                    <span class="blogger-name">{{ $post->user->name }}</span>
                </div>

                <div class="post-body">
                    @if ($post->type != 'book')
                        <img src="{{ $post->getImageUrlAttribute() }}" alt="Post Image" class="post-image">
                        <p class="post-caption">{{ $post->caption }}</p>
                    @else
                        <div class="book-content">
                            <iframe src="{{ $post->getPdfUrlAttribute() }}" frameborder="0"></iframe>
                        </div>
                    @endif
                </div>

                <div class="post-footer">
                    <div class="post-actions">
                        <form action="{{ route('likes.toggle', $post->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit"
                                class="btn {{ auth()->check() && $post->likes->contains('user_id', auth()->id()) ? 'btn-danger' : 'btn-primary' }}">
                                {{ auth()->check() && $post->likes->contains('user_id', auth()->id()) ? 'Unlike' : 'Like' }}
                            </button>
                        </form>
                        <button class="btn btn-secondary">Share</button>
                    </div>
                    <div class="comments-section">
                        <button class="btn btn-info">Comment</button>
                        <button class="btn btn-warning">Reply</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection