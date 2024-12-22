@extends('layout.default_layout')

@section('content')
<div class="container vh-100 d-flex align-items-center justify-content-center">
    <!-- Outer Card -->
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="row g-0 align-items-center">
            <!-- Left Section (Image or Welcome Banner) -->
            <div class="col-md-6 d-none d-md-flex bg-primary text-white text-center flex-column justify-content-center">
                <div class="p-4">
                    <h1 class="display-4 fw-bold">Welcome Back!</h1>
                    {{-- <p class="lead">Login to access your account</p> --}}
                </div>
            </div>

            <!-- Right Section (Login Form) -->
            <div class="col-md-6">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h2 class="h4 text-gray-900">Login to Your Account</h2>
                    </div>
                    <form method="POST" action="{{ route('login') }}" class="user">
                        @csrf
                        <!-- Email Field -->
                        <div class="form-floating mb-3">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                        </div>
                        <!-- Password Field -->
                        <div class="form-floating mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                        </div>
                        <!-- Login Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Login</button>
                        </div>
                    </form>
                    <hr class="my-4">
                    <div class="text-center">
                        <a href="{{ route('password.store') }}" class="small text-decoration-none">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

