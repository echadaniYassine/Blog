@extends('layouts.app')

@section('content')
    <div class="members-section mb-4">
        <h2 class="text-center mb-4">Our Bloggers</h2>
        <div class="row">
            @forelse($members as $member)
                <div class="col-md-3 mb-4">
                    <div class="card text-center h-100">
                        <div class="card-header bg-light">
                            <div class="profile-image-container mb-2">
                                @if($member->profile_image)
                                    <img src="{{ asset('storage/' . $member->profile_image) }}"
                                         alt="{{ $member->name }}"
                                         class="rounded-circle"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default-profile.png') }}"
                                         alt="Default Profile"
                                         class="rounded-circle"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @endif
                            </div>
                            <h5 class="card-title mb-0">{{ $member->name }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-1">
                                <i class="fa fa-envelope"></i> {{ $member->email }}
                            </p>
                            <p>
                                @if($member->bio)
                                    {{ Str::limit($member->bio, 100) }}
                                @else
                                    <span class="text-muted">No bio available</span>
                                @endif
                            </p>
                        </div>
                        <div class="card-footer bg-transparent d-flex justify-content-center gap-2">
                            <a href="{{ route('profile.show', $member->id) }}" class="btn btn-sm btn-primary">View Profile</a>

                            @if(auth()->user()->id === $member->id || auth()->user()->role === 'admin')
                                <a href="{{ route('profile.edit', $member->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-info">No bloggers found.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection