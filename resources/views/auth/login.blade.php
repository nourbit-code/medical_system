@extends('layouts.app')

@section('content')
    <div class="auth-shell">
        <div class="auth-visual">
            <div class="auth-logo"><span>+</span> CarePoint</div>
            <div class="auth-visual-content">
                <div class="auth-icon">♧</div>
                <h1>Your health, organized.</h1>
            </div>
            <div class="auth-footer"></div>
        </div>

        <div class="auth-form-panel">
            <div class="auth-form-card">
                <h2>Sign in</h2>
                <form method="POST" action="{{ url('/login') }}">
                    @csrf
                    @include('auth.fields')

                    <button class="btn btn-primary w-100 py-2 mt-2">Sign in</button>
                </form>

                <p class="text-center text-muted mt-4 mb-0">
                    New to CarePoint?
                    <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Create an account</a>
                </p>
            </div>
        </div>
    </div>
@endsection