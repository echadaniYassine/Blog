@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Welcome to the Blog</h1>

        <!-- News Section -->
        <h2 class="text-primary">Latest News</h2>
        <div class="row mb-5">
            @forelse($news as $post)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('posts.show', $post->id) }}" class="card-link">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <img src="{{ asset('storage/' . ($post->user->profile_image ?? 'default-profile.png')) }}"
                                    loading="lazy" alt="Profile Image" class="rounded-circle"
                                    style="width: 40px; height: 40px; position: absolute; top: 10px; left: 10px;">
                                <div class="pl-5">
                                    <h5 class="mb-0">{{ $post->user->name }}</h5>
                                </div>
                            </div>

                            <!-- Carousel for News Images -->
                            @if ($post->images->count() > 0)
                                <div id="carousel-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach ($post->images as $index => $image)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <img src="{{ $image->url }}" class="d-block w-100" alt="Post Image"
                                                    loading="lazy">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <img src="{{ $post->image_url }}" class="card-img-top" alt="Post Image" loading="lazy">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $post->caption }}</h5>
                            </div>
                        </div>
                    </a>
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
                    <a href="{{ route('posts.show', $post->id) }}" class="card-link">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <img src="{{ asset('storage/' . ($post->user->profile_image ?? 'default-profile.png')) }}"
                                    loading="lazy" alt="Profile Image" class="rounded-circle"
                                    style="width: 40px; height: 40px; position: absolute; top: 10px; left: 10px;">
                                <div class="pl-5">
                                    <h5 class="mb-0">{{ $post->user->name }}</h5>
                                </div>
                            </div>
                            <!-- Display single image for books -->
                            <img src="{{ $post->image_url }}" loading="lazy" class="card-img-top" alt="Post Image">

                            <div class="card-body">
                                <h5 class="card-title">{{ $post->caption }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">No book posts available.</div>
                </div>
            @endforelse
        </div>

        <!-- Normal Posts Section -->
        <h2 class="text-info">Latest Cours</h2>
        <div class="row mb-5">
            @forelse($coursPosts as $post)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('posts.show', $post->id) }}" class="card-link">
                        <div class="card">
                            <div class="card-header d-flex align-items-center" style="position: relative;">
                                <img src="{{ asset('storage/' . ($post->user->profile_image ?? 'default-profile.png')) }}"
                                    alt="Profile Image" class="rounded-circle"
                                    style="width: 40px; height: 40px; position: absolute; top: 10px; left: 10px;">
                                <div class="pl-5" style="margin-left: 50px;">
                                    <h5 class="mb-0">{{ $post->user->name }}</h5>
                                </div>
                            </div>
                            <!-- Display single image for courses -->
                            <img src="{{ $post->image_url }}" loading="lazy" class="card-img-top" alt="Post Image">

                            <div class="card-body">
                                <h5 class="card-title">{{ $post->caption }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">No normal posts available.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
