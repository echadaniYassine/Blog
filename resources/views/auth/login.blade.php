<!-- /views/auth/login.blade.php -->

@extends('layouts.app')

@section('content')
    <h2>Login</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>

    <div style="margin-top: 1rem;">
        <p>
            Don't have an account? 
            <a href="{{ route('register') }}">Register here</a>
        </p>
        <p>
            <a href="{{ route('password.request') }}">Forgot your password?</a>
        </p>
    </div>
@endsection
