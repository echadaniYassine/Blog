<!-- /views/users/profile.blade.php -->


@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Your Profile</h1>

        <!-- Display any success or error messages -->
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @elseif ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile information -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Profile Information</h5>

                <!-- Display profile image if exists -->
                <div class="text-center mb-4">
                    @if (auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile Image"
                            class="rounded-circle" width="100" height="100">
                    @else
                        <img src="https://via.placeholder.com/100" alt="Default Image" class="rounded-circle" width="100"
                            height="100">
                    @endif

                </div>

                <form method="POST" action="{{ route('user.profile', auth()->id()) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name', auth()->user()->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="{{ old('email', auth()->user()->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control"
                            value="{{ old('phone', auth()->user()->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea id="bio" name="bio" class="form-control">{{ old('bio', auth()->user()->bio) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" id="address" name="address" class="form-control"
                            value="{{ old('address', auth()->user()->address) }}">
                    </div>

                    <!-- Password update (optional) -->
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (Optional)</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image (Optional)</label>
                        <input type="file" id="profile_image" name="profile_image" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
@endsection
