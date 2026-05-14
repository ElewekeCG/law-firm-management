<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'YourApp') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #3D7EF5;
            --primary-dark: #2563c7;
            --primary-light: #EEF4FF;
            --text-dark: #1a1e2e;
            --text-muted: #6b7280;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text-dark);
            background: #fff;
            overflow-x: hidden;
        }

        /* ── Navbar ─────────────────────────────────────── */
        .navbar {
            padding: 1.1rem 0;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--text-dark) !important;
            letter-spacing: -0.4px;
        }

        .navbar-brand span { color: var(--primary); }

        .nav-link {
            color: var(--text-muted) !important;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.4rem 0.9rem !important;
            transition: color 0.2s;
        }

        .nav-link:hover { color: var(--text-dark) !important; }

        .btn-nav {
            background: var(--primary);
            color: #fff !important;
            border-radius: 8px;
            padding: 0.45rem 1.2rem !important;
            font-weight: 600;
            font-size: 0.88rem;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-nav:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* ── Hero ────────────────────────────────────────── */
        .hero {
            min-height: calc(100vh - 72px);
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 40%, #f4f7ff 100%);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(61,126,245,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: 10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(61,126,245,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-text {
            animation: fadeUp 0.7s ease both;
        }

        .hero h1 {
            font-size: clamp(2.2rem, 4vw, 3.1rem);
            font-weight: 800;
            line-height: 1.18;
            letter-spacing: -1.2px;
            color: var(--text-dark);
            margin-bottom: 1.2rem;
            font-family: 'Playfair Display', serif;
        }

        .hero h1 .highlight {
            color: var(--primary);
            position: relative;
        }

        .hero h1 .highlight::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary);
            border-radius: 2px;
            opacity: 0.35;
        }

        .hero p.lead {
            color: var(--text-muted);
            font-size: 1.05rem;
            line-height: 1.75;
            max-width: 480px;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        /* Buttons */
        .btn-primary-cta {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.78rem 1.8rem;
            font-weight: 700;
            font-size: 0.88rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 6px 20px rgba(61,126,245,0.35);
        }

        .btn-primary-cta:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(61,126,245,0.4);
            color: #fff;
        }

        .btn-watch {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: gap 0.2s;
        }

        .btn-watch:hover { gap: 0.85rem; color: var(--primary); }

        .play-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(61,126,245,0.35);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-watch:hover .play-icon {
            transform: scale(1.08);
            box-shadow: 0 6px 20px rgba(61,126,245,0.45);
        }

        .play-icon i { color: #fff; font-size: 1rem; margin-left: 3px; }

        /* Hero illustration placeholder */
        .hero-illustration {
            position: relative;
            animation: fadeIn 0.9s ease 0.2s both;
        }

        .hero-illustration img {
            width: 100%;
            max-width: 580px;
            filter: drop-shadow(0 20px 40px rgba(61,126,245,0.12));
        }

        /* ── Features ────────────────────────────────────── */
        .feature-card {
            background: #fff;
            border: 1px solid #eef0f5;
            border-radius: 16px;
            padding: 2rem 1.6rem;
            display: flex;              /* ADD */
            flex-direction: column;     /* ADD */
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }

        .feature-card p {
            flex: 1;                    /* ADD — fills remaining space */
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin: 0;
        }

        .section-label {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--primary);
            margin-bottom: 0.6rem;
        }

        .section-title {
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            font-weight: 800;
            letter-spacing: -0.8px;
            line-height: 1.2;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .section-sub {
            color: var(--text-muted);
            font-size: 1rem;
            max-width: 520px;
            margin: 0 auto 3.5rem;
            line-height: 1.7;
        }

        .feature-card {
            background: #fff;
            border: 1px solid #eef0f5;
            border-radius: 16px;
            padding: 2rem 1.6rem;
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(61,126,245,0.1);
            border-color: rgba(61,126,245,0.2);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
        }

        .feature-icon i { color: var(--primary); font-size: 1.35rem; }

        .feature-card h5 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .feature-card p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin: 0;
        }

        /* ── Stats ───────────────────────────────────────── */
        .stats {
            background: var(--primary);
            padding: 4rem 0;
        }

        .stat-item h2 {
            font-size: 2.6rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 0.3rem;
            letter-spacing: -1px;
        }

        .stat-item p {
            color: rgba(255,255,255,0.75);
            font-size: 0.95rem;
            margin: 0;
            font-weight: 500;
        }

        /* ── CTA ─────────────────────────────────────────── */
        .cta-section {
            padding: 6rem 0;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%);
            text-align: center;
        }

        .cta-section h2 {
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 800;
            letter-spacing: -0.8px;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .cta-section p {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        /* ── Footer ──────────────────────────────────────── */
        footer {
            background: var(--text-dark);
            color: rgba(255,255,255,0.55);
            padding: 2rem 0;
            font-size: 0.88rem;
        }

        footer a { color: rgba(255,255,255,0.55); text-decoration: none; }
        footer a:hover { color: #fff; }

        /* ── Animations ──────────────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ──────────────────────────────────── */
        @media (max-width: 768px) {
            .hero { text-align: center; padding: 3rem 0; min-height: auto; }
            .hero p.lead { margin: 0 auto 2rem; }
            .hero-buttons { justify-content: center; }
            .hero-illustration { margin-top: 2.5rem; text-align: center; }
            .stat-item { margin-bottom: 2rem; }
        }
    </style>
</head>
<body>

    {{-- ── Navbar ── --}}
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('assets/img/law-firm-logo.svg') }}"
                alt="logo"
                style="width: 10rem; height: auto;"
                >
            </a>

            <button class="navbar-toggler border-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                    @auth
                        <li class="nav-item ms-2">
                            <a class="nav-link btn-nav" href="{{ url('/dashboard') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Log in</a>
                        </li>
                        <li class="nav-item ms-1">
                            <a class="nav-link btn-nav" href="{{ route('reg') }}">Get Started</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- ── Hero ── --}}
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">

                {{-- Left: Text --}}
                <div class="col-lg-6 hero-text">
                    <h1>
                        The Smarter Way to<br>
                        <span class="highlight">Manage Your Law Firm</span>
                    </h1>
                    <p class="lead">
                        From case tracking to client management, Law Pilot gives
                        you full control of your practice in one dashboard.
                    </p>
                    <div class="d-flex flex-wrap align-items-center gap-4 hero-buttons">
                        <a href="{{ route('reg') }}" class="btn-primary-cta">
                            Learn More
                        </a>
                        <a href="{{ route('reg') }}" class="btn-primary-cta">
                            Get Started
                        </a>
                    </div>
                    {{-- <p style="margin-top: 1.2rem; font-size: 0.85rem; color: var(--text-muted);">
                        <i class="bi bi-check-circle-fill" style="color: var(--primary);"></i>
                        Set up in under 10 minutes &nbsp;·&nbsp;
                        <i class="bi bi-check-circle-fill" style="color: var(--primary);"></i>
                        No credit card required
                    </p> --}}
                </div>

                {{-- Right: Illustration --}}
                <div class="col-lg-6 hero-illustration text-center">
                    {{-- Replace the src below with your own illustration --}}
                    <img
                        src="{{ asset('assets/img/Company-amico.svg') }}"
                        alt="Digital transformation illustration"
                        onerror="this.style.display='none'"
                    >
                </div>

            </div>
        </div>
    </section>

    {{-- ── Features ── --}}
    <section class="features" id="features">
    <div class="container">
        <div class="text-center">
            <p class="section-label">What we offer</p>
            <h2 class="section-title">Everything your firm needs</h2>
            <p class="section-sub">
                Law Pilot is purpose-built for legal teams to manage cases,
                clients, documents, and billing all in one place.
            </p>
        </div>

        <div class="row g-4 align-items-stretch">
            @foreach ([
                ['bi-folder2-open',       'Case Management',     'Track every case from intake to resolution with full history and status updates.'],
                ['bi-people-fill',        'Client Portal',       'Give clients secure access to their case files, documents, and billing statements.'],
                ['bi-file-earmark-text',  'Document Automation', 'Generate contracts, letters, and court documents in seconds using smart templates.'],
                ['bi-cash-stack',         'Property Management', 'Manage properties with ease, generate reports, and get payments faster with online payments.'],
                ['bi-calendar2-check',    'Deadline Tracking',   'Never miss a court date or filing deadline with automated reminders and alerts.'],
                ['bi-shield-lock-fill',   'Secure & Compliant',  'Bank-level encryption and role-based access keeps your client data fully protected.'],
            ] as [$icon, $title, $desc])
            <div class="col-sm-6 col-lg-4 d-flex">
                <div class="feature-card h-100 w-100">
                    <div class="feature-icon">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <h5>{{ $title }}</h5>
                    <p>{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

    {{-- ── Stats ── --}}
    {{-- <section class="stats">
        <div class="container">
            <div class="row text-center g-4">
                @foreach ([
                    ['10K+',  'Active Users'],
                    ['99.9%', 'Uptime SLA'],
                    ['150+',  'Integrations'],
                    ['4.9★',  'Average Rating'],
                ] as [$num, $label])
                <div class="col-6 col-md-3 stat-item">
                    <h2>{{ $num }}</h2>
                    <p>{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section> --}}

    {{-- ── CTA ── --}}
    <section class="cta-section">
        <div class="container">
            <h2>Ready to transform your practice?</h2>
            <p>Join the law firms already using Law Pilot to work smarter.</p>
            <a href="{{ route('reg') }}" class="btn-primary-cta">
                Get Started
            </a>
        </div>
    </section>

    {{-- ── Footer ── --}}
    <footer>
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
            <span>
                © {{ date('Y') }} Law Pilot. All rights reserved.
                {{-- Storyset attribution --}}
                <small style="width:100%; text-align:center; color: rgba(255,255,255,0.4);">
                         Illustrations by <a href="https://storyset.com" target="_blank" rel="noopener" style="color:rgba(255,255,255,0.55);">Storyset</a>
                </small>
            </span>

            <div class="d-flex gap-4">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Contact</a>
            </div>
        </div>

    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
