@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-header text-center bg-light">
            <div class="mb-3">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" 
                         alt="{{ $user->name }}" 
                         class="rounded-circle" 
                         style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <img src="{{ asset('images/default-profile.png') }}" 
                         alt="Default Profile" 
                         class="rounded-circle" 
                         style="width: 120px; height: 120px; object-fit: cover;">
                @endif
            </div>
            <h4 class="mb-0">{{ $user->name }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            @if($user->phone)
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
            @endif
            <p><strong>Bio:</strong> {{ $user->bio ?? 'No bio available' }}</p>
        </div>
        <div class="card-footer text-center bg-white">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
            @if(auth()->user()->id === $user->id) <!-- Check if the logged-in user is viewing their own profile -->
                <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-warning">Edit Profile</a>
            @endif
        </div>
    </div>
</div>
@endsection