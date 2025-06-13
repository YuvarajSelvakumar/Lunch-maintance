@extends('layouts.guest')

@section('content')
<div class="auth-section">
    <div class="auth-card" data-aos="fade-up">
        <h4 class="text-center mb-4">Forgot Your Password?</h4>
        <p class="text-center text-muted mb-3">Enter your email to receive a password reset link.</p>

        @if (session('status'))
            <div class="alert alert-success text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary rounded-pill">Send Reset Link</button>
            </div>

            <div class="mt-3 text-center">
                <a href="{{ route('login') }}" class="text-decoration-none">← Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
