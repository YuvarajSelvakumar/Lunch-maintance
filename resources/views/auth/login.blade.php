@extends('layouts.guest')

@section('content')
@if(auth()->check())
    <script>window.location.href = "{{ route('menu-pricing') }}";</script>
@endif

<div class="auth-section">
    <div class="auth-card" data-aos="zoom-in">
        <h3 class="text-center mb-4">Login</h3>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" class="form-control" type="password" name="password" required>
                @error('password')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                @enderror

                <!-- Forgot Password Link -->
                <div class="mt-2 text-end">
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-muted small">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            <div class="d-grid">
                <button class="btn btn-success rounded-pill" type="submit">Login</button>
            </div>

            <div class="mt-3 text-center">
                <a href="{{ route('register') }}" class="text-decoration-none">Don't have an account?</a>
            </div>
        </form>
    </div>
</div>
@endsection
