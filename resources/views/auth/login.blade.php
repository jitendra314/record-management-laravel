@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center px-3">

        <div class="w-100" style="max-width: 420px;">

            <div class="text-center mb-4">
                <h1 class="fw-bold fs-2">Welcome Back</h1>
                <p class="text-muted">Sign in to continue</p>
            </div>

            <div class="bg-white p-4 p-sm-5 rounded-4 shadow">

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg"
                            placeholder="you@example.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••"
                            required>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember">
                        <label class="form-check-label">Remember me</label>
                    </div>

                    <button class="btn btn-primary btn-lg w-100 py-3">
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>
@endsection
