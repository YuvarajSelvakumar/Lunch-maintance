@extends('layouts.guest')

@section('content')
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<style>
    body {
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .welcome-section {
        min-height: 100vh;
        background: linear-gradient(135deg, #f6d365, #fda085);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .welcome-card {
        background-color: #fff;
        border-radius: 1.5rem;
        padding: 3rem 2rem;
        text-align: center;
        max-width: 600px;
        width: 100%;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .btn-animated {
        transition: all 0.3s ease-in-out;
        border-radius: 50px;
        padding: 0.75rem 2rem;
        font-weight: 600;
    }

    .btn-animated:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="welcome-section">
    <div class="welcome-card" data-aos="fade-up">
        <h1 class="mb-3 fw-bold">Welcome to Lunch Maintenance</h1>
        <p class="mb-4 text-muted">Track meals, manage pricing, and handle vendor payments with ease.</p>

        <a href="{{ route('login') }}" class="btn btn-primary btn-animated me-2">Login</a>
        <a href="{{ route('register') }}" class="btn btn-outline-dark btn-animated">Register</a>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>
@endsection
