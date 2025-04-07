@extends('layouts.app')

@section('content')
    <h2>Forgot Password</h2>

    @if (session('status'))
        <p style="color: green;">{{ session('status') }}</p>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <button type="submit">Send Password Reset Link</button>
    </form>

    <p style="margin-top: 1rem;">
        Remember your password?
        <a href="{{ route('login') }}">Login</a>
    </p>
@endsection
