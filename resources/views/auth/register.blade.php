@extends('layout.default_layout')

@section('content')

<style>
    :root {
        --primary: #3D7EF5;
    }

    body {
        font-family: 'DM Sans', sans-serif;
        background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 40%, #f4f7ff 100%);
    }

    .btn-primary {
        background-color: var(--primary);
        border: none;
    }

    .btn-primary:hover {
        background-color: #2563c7;
    }

    .form-control {
        border-radius: 0.6rem;
    }

    .role-btn.active {
        background-color: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
</style>

<div class="container min-vh-100 d-flex flex-column justify-content-center">

    {{-- Card --}}
    <div class="card shadow-lg p-4 p-md-5 mx-auto w-100" style="max-width: 520px; border-radius: 1rem;">
        <div class="text-center mb-3">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/img/law-firm-logo.svg') }}"
                    alt="Law Pilot Logo"
                    class="img-fluid"
                    style="max-width: 160px;">
            </a>
        </div>
        <h3 class="text-center fw-bold mb-1">Create Your Account</h3>
        <p class="text-muted text-center mb-4"></p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row g-3">

                {{-- Full Name --}}
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold">Full Name</label>
                    <div class="input-group">
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="John Doe"
                               value="{{ old('name') }}"
                               required>
                    </div>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold">Email Address</label>
                    <div class="input-group">
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="john@lawfirm.com"
                               value="{{ old('email') }}"
                               required>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold">Password</label>
                    <div class="input-group">
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min. 8 characters"
                               required>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold">Confirm Password</label>
                    <div class="input-group">
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Repeat password"
                               required>
                    </div>
                </div>

                {{-- Role --}}
               <div class="col-12 mt-2">
                    <hr>
                    <label class="form-label small fw-semibold mb-2">I am a:</label>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary w-100 role-btn active" data-value="lawyer">
                            <i class="bi bi-briefcase me-1"></i> Lawyer
                        </button>

                        <button type="button" class="btn btn-outline-primary w-100 role-btn" data-value="client">
                            <i class="bi bi-person me-1"></i> Client
                        </button>

                        <button type="button" class="btn btn-outline-primary w-100 role-btn" data-value="clerk">
                            <i class="bi bi-file-earmark-text me-1"></i> Clerk
                        </button>
                    </div>

                    {{-- Hidden input for form submission --}}
                    <input type="hidden" name="role" id="roleInput" value="lawyer">

                </div>

                {{-- Submit --}}
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        Create Account
                    </button>
                </div>

            </div>
        </form>

        <p class="text-center text-muted small mt-3">
            Already have an account?
            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Log in</a>
        </p>

    </div>
</div>

<script>
    const buttons = document.querySelectorAll('.role-btn');
    const input = document.getElementById('roleInput');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            // remove active from all
            buttons.forEach(b => b.classList.remove('active'));

            // add active to clicked
            btn.classList.add('active');

            // update hidden input
            input.value = btn.getAttribute('data-value');
        });
    });
</script>
@endsection
