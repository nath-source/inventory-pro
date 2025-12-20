@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0 fw-bold">{{ __('Reset Password') }}</h4>
                </div>

                <div class="card-body p-5">
                    <div class="mb-4 text-muted text-center">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                {{ __('Email Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left"></i> Back to <span class="text-primary fw-bold">Login</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection