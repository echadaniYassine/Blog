<!-- /views/users/dashboard.blade.php -->


@extends('layouts.app')

@section('content')
    <h1>Welcome, {{ $user->name }}!</h1>

    <p>Email: {{ $user->email }}</p>
    <p>Role: {{ ucfirst($user->role) }}</p>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
@endsection
