<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Coffee Shop Back Office' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <style>
        :root {
            --mc-canvas: #F3F0EE;
            --mc-lifted: #FCFBFA;
            --mc-ink: #141413;
            --mc-charcoal: #262627;
            --mc-slate: #696969;
            --mc-line: rgba(20, 20, 19, 0.16);
            --mc-accent: #F37338;
            --mc-signal: #CF4500;
            --mc-ok: #1b7d4a;
            --mc-danger: #a6211a;
            --radius-btn: 20px;
            --radius-stadium: 40px;
            --radius-pill: 999px;
            --shadow-nav: rgba(0, 0, 0, 0.04) 0 4px 24px;
            --shadow-soft: rgba(0, 0, 0, 0.08) 0 24px 48px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: var(--mc-canvas);
            color: var(--mc-ink);
            font-family: "Sofia Sans", Arial, sans-serif;
        }

        body {
            min-height: 100vh;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            color: var(--mc-ink);
            letter-spacing: -0.02em;
        }

        h1 {
            font-size: clamp(2rem, 3.2vw, 4rem);
            line-height: 1;
            font-weight: 500;
        }

        h2 {
            font-size: clamp(1.7rem, 2.2vw, 2.25rem);
            line-height: 1.15;
            font-weight: 500;
        }

        h3 {
            font-size: clamp(1.2rem, 1.5vw, 1.5rem);
            line-height: 1.2;
            font-weight: 500;
        }

        p,
        li,
        td,
        th,
        label,
        input,
        select,
        textarea,
        button {
            font-size: 16px;
            line-height: 1.4;
            font-weight: 450;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .eyebrow {
            margin: 0 0 0.6rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--mc-slate);
        }

        .eyebrow::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--mc-accent);
            flex: 0 0 8px;
        }

        .site-wrap {
            position: relative;
            overflow-x: clip;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .site-wrap::before,
        .site-wrap::after {
            content: "";
            position: fixed;
            border: 1.2px solid color-mix(in srgb, var(--mc-accent) 70%, transparent);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.75;
        }

        .site-wrap::before {
            width: 1200px;
            height: 1200px;
            top: -900px;
            right: -260px;
            transform: rotate(-16deg);
        }

        .site-wrap::after {
            width: 900px;
            height: 900px;
            left: -540px;
            top: 250px;
            transform: rotate(11deg);
        }

        .top-shell {
            position: sticky;
            top: 0;
            z-index: 20;
            padding: 1.2rem 1rem 0;
        }

        .nav-pill {
            max-width: 1280px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.94);
            border-radius: var(--radius-pill);
            box-shadow: var(--shadow-nav);
            border: 1px solid rgba(20, 20, 19, 0.04);
            min-height: 64px;
            padding: 0.55rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            white-space: nowrap;
        }

        .brand-mark {
            position: relative;
            width: 32px;
            height: 20px;
        }

        .brand-mark::before,
        .brand-mark::after {
            content: "";
            position: absolute;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }

        .brand-mark::before {
            left: 0;
            background: #EB001B;
        }

        .brand-mark::after {
            right: 0;
            background: #F79E1B;
            mix-blend-mode: multiply;
        }

        .nav-links {
            flex: 1;
            display: flex;
            justify-content: center;
            gap: clamp(0.8rem, 3vw, 3rem);
            align-items: center;
            min-width: 0;
        }

        .nav-links a {
            font-weight: 500;
            letter-spacing: -0.03em;
            white-space: nowrap;
            padding: 0.35rem 0.2rem;
            border-bottom: 2px solid transparent;
        }

        .nav-links a:hover,
        .nav-links a:focus-visible {
            border-bottom-color: var(--mc-ink);
            outline: none;
        }

        .nav-right {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            margin-left: auto;
        }

        .circle-btn {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid var(--mc-line);
            background: #fff;
            font-size: 1.15rem;
            line-height: 1;
        }

        .mobile-nav {
            display: none;
        }

        .content {
            position: relative;
            z-index: 1;
            width: min(1280px, calc(100% - 2rem));
            margin: 1.5rem auto 0;
            padding-bottom: 4rem;
            flex: 1;
        }

        .hero {
            border-radius: var(--radius-stadium);
            background: linear-gradient(120deg, #1f1f1d 0%, #2d2d2a 48%, #1c1c1a 100%);
            color: var(--mc-canvas);
            padding: clamp(1.4rem, 2.4vw, 2.8rem);
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-soft);
        }

        .hero .eyebrow {
            color: rgba(243, 240, 238, 0.85);
        }

        .hero .eyebrow::before {
            background: var(--mc-accent);
        }

        .hero p {
            margin: 0.9rem 0 0;
            color: rgba(243, 240, 238, 0.88);
            max-width: 70ch;
        }

        .card {
            background: var(--mc-lifted);
            border-radius: var(--radius-stadium);
            border: 1px solid rgba(20, 20, 19, 0.08);
            padding: clamp(1rem, 2vw, 1.6rem);
            margin-bottom: 1rem;
        }

        .card-pill {
            border-radius: var(--radius-pill);
        }

        .metric {
            font-size: clamp(2rem, 4vw, 3.2rem);
            line-height: 1;
            font-weight: 500;
            letter-spacing: -0.02em;
            margin: 0.4rem 0;
        }

        .muted {
            color: var(--mc-slate);
            margin: 0.35rem 0 0;
        }

        .grid {
            display: grid;
            gap: 1rem;
        }

        .grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .form-grid {
            display: grid;
            gap: 0.85rem;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        label {
            display: grid;
            gap: 0.35rem;
            color: var(--mc-charcoal);
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid var(--mc-line);
            border-radius: 24px;
            padding: 0.65rem 0.95rem;
            background: #fff;
            color: var(--mc-ink);
            outline: none;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--mc-ink);
            box-shadow: 0 0 0 2px rgba(20, 20, 19, 0.08);
        }

        .inline-check {
            display: inline-flex;
            width: auto;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .inline-check input {
            width: 18px;
            height: 18px;
            padding: 0;
            border-radius: 6px;
        }

        .btn,
        button {
            border: 1.5px solid var(--mc-ink);
            border-radius: var(--radius-btn);
            background: #fff;
            color: var(--mc-ink);
            padding: 0.42rem 1.2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            cursor: pointer;
            font-weight: 500;
            letter-spacing: -0.03em;
            min-height: 44px;
        }

        .btn-main {
            background: var(--mc-ink);
            color: var(--mc-canvas);
            border-color: var(--mc-ink);
        }

        .btn-signal {
            background: var(--mc-signal);
            color: #fff;
            border: none;
            border-radius: 24px;
        }

        .btn-ghost {
            background: transparent;
        }

        .btn-sm {
            min-height: 38px;
            padding: 0.25rem 0.95rem;
            font-size: 14px;
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            align-items: center;
        }

        .table-wrap {
            overflow-x: auto;
            border-radius: 26px;
            border: 1px solid rgba(20, 20, 19, 0.08);
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 680px;
        }

        th,
        td {
            text-align: left;
            padding: 0.72rem 0.8rem;
            border-bottom: 1px solid rgba(20, 20, 19, 0.09);
            vertical-align: top;
        }

        th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
            color: var(--mc-slate);
            background: #faf8f6;
        }

        .status-pending {
            color: #9a3a0a;
            font-weight: 700;
        }

        .status-approved {
            color: var(--mc-ok);
            font-weight: 700;
        }

        .status-rejected {
            color: var(--mc-danger);
            font-weight: 700;
        }

        .flash {
            border-radius: 24px;
            padding: 0.8rem 1rem;
            margin-bottom: 0.8rem;
            border: 1px solid;
            background: #fff;
        }

        .flash-ok {
            border-color: color-mix(in srgb, var(--mc-ok) 30%, transparent);
            color: #0c5d34;
        }

        .flash-err {
            border-color: color-mix(in srgb, var(--mc-danger) 30%, transparent);
            color: #7f1d18;
        }

        .footer {
            background: var(--mc-ink);
            color: #fff;
            padding: 3rem 1rem 2rem;
        }

        .footer-inner {
            width: min(1280px, 100%);
            margin: 0 auto;
        }

        .footer h2 {
            color: #fff;
            margin-bottom: 2rem;
            max-width: 20ch;
        }

        .footer-grid {
            display: grid;
            gap: 1.1rem;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 2rem;
        }

        .footer-col h4 {
            color: rgba(255, 255, 255, 0.72);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.7rem;
            font-weight: 700;
        }

        .footer-col a {
            display: block;
            color: #fff;
            opacity: 0.92;
            margin: 0.34rem 0;
            font-size: 14px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding-top: 0.95rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.65rem;
            color: rgba(255, 255, 255, 0.78);
            font-size: 14px;
        }

        @media (max-width: 1023px) {
            .nav-links {
                display: none;
            }

            .mobile-nav {
                display: block;
                margin-left: auto;
            }

            .mobile-nav summary {
                list-style: none;
                border: 1px solid var(--mc-line);
                border-radius: 50%;
                width: 44px;
                height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                background: #fff;
                cursor: pointer;
            }

            .mobile-panel {
                margin-top: 0.55rem;
                background: #fff;
                border-radius: 24px;
                border: 1px solid var(--mc-line);
                padding: 0.65rem;
                display: grid;
                gap: 0.4rem;
            }

            .mobile-panel a {
                padding: 0.55rem 0.75rem;
                border-radius: 14px;
                border: 1px solid transparent;
            }

            .mobile-panel a:hover {
                border-color: var(--mc-line);
                background: #faf8f6;
            }

            .grid-3 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .content {
                width: calc(100% - 1rem);
                margin-top: 1rem;
            }

            .top-shell {
                padding: 0.75rem 0.5rem 0;
            }

            .nav-pill {
                padding: 0.45rem 0.55rem;
            }

            .brand span:last-child {
                display: none;
            }

            .grid-2,
            .grid-3,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .site-wrap::before,
            .site-wrap::after {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="site-wrap">
    @auth
        <header class="top-shell">
            <div class="nav-pill">
                <a class="brand" href="{{ route('dashboard') }}">
                    <span class="brand-mark"></span>
                    <span>Coffee Back-Office</span>
                </a>

                <nav class="nav-links">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    @if(auth()->user()->isOwner())
                        <a href="{{ route('owner.staff.index') }}">Staff</a>
                        <a href="{{ route('owner.kpi.index') }}">KPI</a>
                        <a href="{{ route('owner.validations.index') }}">Validasi</a>
                        <a href="{{ route('owner.finance') }}">Finance</a>
                        <a href="{{ route('owner.payroll.index') }}">Payroll</a>
                    @else
                        <a href="{{ route('staff.attendance') }}">Presensi</a>
                        <a href="{{ route('staff.shifts') }}">Shift</a>
                        <a href="{{ route('staff.expenses') }}">Expense</a>
                        <a href="{{ route('staff.kpi') }}">KPI Harian</a>
                    @endif
                </nav>

                <div class="nav-right">
                    <a class="circle-btn" href="{{ route('dashboard') }}" aria-label="Search">⌕</a>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-sm" type="submit">Logout</button>
                    </form>
                </div>

                <details class="mobile-nav">
                    <summary>☰</summary>
                    <nav class="mobile-panel">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        @if(auth()->user()->isOwner())
                            <a href="{{ route('owner.staff.index') }}">Staff</a>
                            <a href="{{ route('owner.kpi.index') }}">KPI</a>
                            <a href="{{ route('owner.validations.index') }}">Validasi</a>
                            <a href="{{ route('owner.finance') }}">Finance</a>
                            <a href="{{ route('owner.payroll.index') }}">Payroll</a>
                        @else
                            <a href="{{ route('staff.attendance') }}">Presensi</a>
                            <a href="{{ route('staff.shifts') }}">Shift</a>
                            <a href="{{ route('staff.expenses') }}">Expense</a>
                            <a href="{{ route('staff.kpi') }}">KPI Harian</a>
                        @endif
                    </nav>
                </details>
            </div>
        </header>
    @endauth

    <main class="content">
        @if(session('success'))
            <div class="flash flash-ok">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="flash flash-err">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('hero')
        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer-inner">
            <h2>We are always here when your coffee operations need clarity.</h2>

            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Platform</h4>
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('login') }}">Akses Akun</a>
                </div>
                <div class="footer-col">
                    <h4>Operasional</h4>
                    <a href="#">Presensi</a>
                    <a href="#">Shift Recon</a>
                    <a href="#">KPI Harian</a>
                </div>
                <div class="footer-col">
                    <h4>Keuangan</h4>
                    <a href="#">Multichannel Revenue</a>
                    <a href="#">Expense & Nota</a>
                    <a href="#">Payroll</a>
                </div>
                <div class="footer-col">
                    <h4>Bantuan</h4>
                    <a href="#">Panduan Implementasi</a>
                    <a href="#">Kebijakan Data</a>
                    <a href="#">Dukungan Sistem</a>
                </div>
            </div>

            <div class="footer-bottom">
                <span>© {{ now()->year }} Coffee Back-Office System</span>
                <span>Canvas Cream • Ink Pill • Orbit Layout</span>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
