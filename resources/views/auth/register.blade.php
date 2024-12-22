@extends('layout.default_layout')

@section('content')
<div class="container vh-100 d-flex align-items-center justify-content-center">
    <!-- Registration Card -->
    <div class="card shadow-lg border-0 rounded-lg w-100" style="max-width: 850px;">
        <div class="row g-0">
            <!-- Sidebar for Branding -->
            <div class="col-lg-5 d-none d-lg-flex align-items-center justify-content-center bg-primary text-white p-4">
                <div class="text-center">
                    <h3 class="h3 mb-4">Law Hub</h3>
                    <img src="{{ url('assets/img/Justice_law_firm_logo-removebg-preview.png') }}" alt="law" style="max-width: 100%; height: auto; width: 150px;">
                    {{-- <p class="lead">Streamline appointments, cases, and client management.</p> --}}
                    {{-- <i class="fas fa-gavel fa-3x"></i> --}}
                </div>
            </div>
            <!-- Registration Form -->
            <div class="col-lg-7">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h2 class="h4 text-gray-900">Create Your Account</h2>
                        <p class="text-muted">Join us to manage your cases efficiently.</p>
                    </div>
                    <form class="user" method="POST" action="{{ route('register') }}">
                        @csrf
                        <!-- Full Name and Email -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="name" type="text" class="form-control" id="name" placeholder="Full Name" required>
                                    {{-- <label for="name">Full Name</label> --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="email" type="email" class="form-control" id="email" placeholder="Email Address" required>
                                    {{-- <label for="email">Email Address</label> --}}
                                </div>
                            </div>
                        </div>

                        <!-- Password and Confirm Password -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
                                    {{-- <label for="password">Password</label> --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="password_confirmation" type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" required>
                                    {{-- <label for="password_confirmation">Confirm Password</label> --}}
                                </div>
                            </div>
                        </div>

                        <!-- User Role -->
                        <div class="mb-3 d-flex">
                            <label class="form-label" style="padding: 0 1rem;">I am a:</label>
                            <div class="form-check" style="padding: 0 1rem;">
                                <input class="form-check-input" type="radio" name="role" id="roleLawyer" value="lawyer" required>
                                <label class="form-check-label" for="roleLawyer">Lawyer</label>
                            </div>
                            <div class="form-check" style="padding: 0 1rem;">
                                <input class="form-check-input" type="radio" name="role" id="roleClient" value="client">
                                <label class="form-check-label" for="roleClient">Client</label>
                            </div>
                            <div class="form-check" >
                                <input class="form-check-input" type="radio" name="role" id="roleClerk" value="clerk">
                                <label class="form-check-label" for="roleClerk">Clerk</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-lg">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
