@extends('layouts.guest')

@section('content')
<div class="auth-section">
    <div class="auth-card" data-aos="zoom-in">
        <h4 class="text-center mb-4">Reset Your Password</h4>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input id="password" type="password" name="password" class="form-control" required>
                @error('password')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success rounded-pill">Reset Password</button>
            </div>

            <div class="mt-3 text-center">
                <a href="{{ route('login') }}" class="text-decoration-none">← Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
