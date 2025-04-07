@extends('layouts.app')

@section('content')
    <h2>Register</h2>
    
    <form action="{{ route('register') }}" method="POST">
        @csrf

        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Confirm Password:</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit">Register</button>
    </form>

    <p style="margin-top: 1rem;">
        Already have an account?
        <a href="{{ route('login') }}">Login here</a>.
    </p>
@endsection
