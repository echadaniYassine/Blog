@extends('layouts.app')
@section('content')
    <div class="members-section mb-4">
        <h2>Our Members</h2>
        <div class="row">
            @forelse($members as $member)
                <div class="col-md-3 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">{{ $member->name }}</h5>
                            <p class="card-text">{{ $member->email }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p>No members available.</p>
            @endforelse
        </div>
    </div>
@endsection
