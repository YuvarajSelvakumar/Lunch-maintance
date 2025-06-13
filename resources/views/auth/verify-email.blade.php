@extends('layouts.guest')

@section('content')
<div class="auth-section">
    <div class="auth-card" data-aos="fade-in">
        <h4 class="text-center mb-4">Email Verification Required</h4>

        <div class="text-muted text-center mb-3">
            Thanks for signing up! Before getting started, could you verify your email address
            by clicking the link we just emailed to you? If you didn’t receive the email,
            we will gladly send you another.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success text-center">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
            @csrf
            <div class="d-grid">
                <button type="submit" class="btn btn-primary rounded-pill">
                    Resend Verification Email
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="d-grid">
                <button type="submit" class="btn btn-outline-secondary rounded-pill">
                    Logout
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
