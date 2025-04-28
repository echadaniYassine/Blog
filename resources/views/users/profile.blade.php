@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-header text-center bg-light">
                <div class="mb-3">
                    @if ($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}"
                            class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile" class="rounded-circle"
                            style="width: 120px; height: 120px; object-fit: cover;">
                    @endif
                </div>
                <h4 class="mb-0">{{ $user->name }}</h4>
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                @if ($user->phone)
                    <p><strong>Phone:</strong> {{ $user->phone }}</p>
                @endif
                <p><strong>Bio:</strong> {{ $user->bio ?? 'No bio available' }}</p>

                <div>
                    <strong>PDF:</strong>
                    <!-- Button to view PDF -->
                    <a href="{{ asset('storage/' . $user->pdf) }}" target="_blank" class="btn btn-primary">View PDF</a>

                    <!-- Button to download PDF -->
                    <a href="{{ asset('storage/' . $user->pdf) }}" download class="btn btn-secondary">Download PDF</a>
                </div>

            </div>


            <!-- Display Books, News, and Courses -->
            <div class="card-body">
                <!-- Display Books -->
                <h5 class="text-center mb-3">Books by {{ $user->name }}</h5>
                @forelse($user->posts->where('type', 'book') as $post)
                    <div class="mb-2">
                        <strong>{{ $post->caption }}</strong><br>
                        <img src="{{ $post->imageUrl }}" alt="Book Image" style="max-width: 100px;">
                        <br>
                        @if ($post->pdf_url)
                            <a href="{{ $post->pdfUrl }}" class="btn btn-sm btn-primary">View PDF</a>
                        @endif
                    </div>
                @empty
                    <p>No books available.</p>
                @endforelse

                <!-- Display News -->
                <h5 class="text-center mt-4 mb-3">News by {{ $user->name }}</h5>
                @forelse($user->posts->where('type', 'news') as $post)
                    <div class="mb-2">
                        <strong>{{ $post->caption }}</strong><br>
                        <img src="{{ $post->imageUrl }}" alt="News Image" style="max-width: 100px;">
                    </div>
                @empty
                    <p>No news available.</p>
                @endforelse

                <!-- Display Courses -->
                <h5 class="text-center mt-4 mb-3">Courses by {{ $user->name }}</h5>
                @forelse($user->posts->where('type', 'course') as $post)
                    <div class="mb-2">
                        <strong>{{ $post->caption }}</strong><br>
                        <img src="{{ $post->imageUrl }}" alt="Course Image" style="max-width: 100px;">
                    </div>
                @empty
                    <p>No courses available.</p>
                @endforelse
            </div>

            <div class="card-footer text-center bg-white">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                @if (auth()->user()->id === $user->id)
                    <!-- Check if the logged-in user is viewing their own profile -->
                    <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-warning">Edit Profile</a>
                @endif
            </div>
        </div>
    </div>
@endsection
