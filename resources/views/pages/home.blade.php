@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Welcome to the Blog</h1>

        <!-- News Section -->
        <h2 class="text-primary">Latest News</h2>
        <div class="row mb-5">
            @forelse($news as $post)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('posts.show', $post->id) }}" class="card-link text-decoration-none">
                        <div class="card h-100">
                            <div class="card-header d-flex align-items-center position-relative">
                                <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-profile.png') }}" 
                                    loading="lazy" alt="Profile Image" class="rounded-circle"
                                    style="width: 40px; height: 40px; position: absolute; top: 10px; left: 10px;">
                                <div class="ps-5">
                                    <h5 class="mb-0">{{ $post->user->name }}</h5>
                                </div>
                            </div>

                            <!-- Carousel for News Images -->
                            @php
                                // Déterminer si $post->images est un tableau ou une chaîne JSON
                                $images = is_array($post->images) ? $post->images : json_decode($post->images ?? '[]', true);
                            @endphp

                            @if (!empty($images))
                                <div id="carousel-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach ($images as $index => $image)
                                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                <img src="{{ asset('storage/' . $image) }}" class="d-block w-100"
                                                    alt="Post Image" loading="lazy" style="height: 200px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (count($images) > 1)
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('images/default-post.jpg') }}" 
                                    class="card-img-top" alt="Post Image" loading="lazy" style="height: 200px; object-fit: cover;">
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

      
    </div>
@endsection