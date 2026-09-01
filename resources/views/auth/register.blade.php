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

            <h2>Create your account</h2>


            <form method="POST" action="{{ url('/register') }}">
                @csrf
                @include('auth.fields')

                <button class="btn btn-primary w-100 py-2 mt-2">Create account</button>
            </form>

            <p class="text-center text-muted mt-4 mb-0">
                Already registered?
                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection