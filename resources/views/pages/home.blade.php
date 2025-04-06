<!-- /views/pages/home.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Welcome to the Blog</h1>

        <!-- News Section -->
        <h2 class="text-primary">Latest News</h2>
        <div class="row mb-5">
            @forelse($news as $post)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <img src="{{ $post->user->profile_image }}" alt="Profile Image" class="rounded-circle"
                                style="width: 40px; height: 40px; margin-right: 10px;">
                            <h5 class="mb-0">{{ $post->user->name }}</h5>
                        </div>
                        <img src="{{ $post->getImageUrlAttribute() }}" class="card-img-top" alt="Post Image">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->caption }}</h5>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">No news posts available.</div>
                </div>
            @endforelse
        </div>

        <!-- Books Section -->
        <h2 class="text-success">Latest Books</h2>
        <div class="row mb-5">
            @forelse($books as $post)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <img src="{{ $post->user->profile_image }}" alt="Profile Image" class="rounded-circle"
                                style="width: 40px; height: 40px; margin-right: 10px;">
                            <h5 class="mb-0">{{ $post->user->name }}</h5>
                        </div>
                        <img src="{{ $post->getImageUrlAttribute() }}" class="card-img-top" alt="Post Image">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->caption }}</h5>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">No book posts available.</div>
                </div>
            @endforelse
        </div>

        <!-- Normal Posts Section -->
        <h2 class="text-info">Latest Posts</h2>
        <div class="row mb-5">
            @forelse($normalPosts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <img src="{{ $post->user->profile_image }}" alt="Profile Image" class="rounded-circle"
                                style="width: 40px; height: 40px; margin-right: 10px;">
                            <h5 class="mb-0">{{ $post->user->name }}</h5>
                        </div>
                        <img src="{{ $post->getImageUrlAttribute() }}" class="card-img-top" alt="Post Image">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->caption }}</h5>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">No normal posts available.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
