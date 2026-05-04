<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Coffee Shop Back Office' }}</title>
    <style>
        :root {
            --bn-primary: #fcd535;
            --bn-primary-active: #f0b90b;
            --bn-ink: #181a20;
            --bn-canvas: #0b0e11;
            --bn-card: #1e2329;
            --bn-elevated: #2b3139;
            --bn-body: #eaecef;
            --bn-muted: #707a8a;
            --bn-line: #2b3139;
            --bn-up: #0ecb81;
            --bn-down: #f6465d;
            --bn-info: #3b82f6;
            --radius-xs: 4px;
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-pill: 9999px;
            --shadow-soft: 0 10px 28px rgba(0, 0, 0, 0.35);
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            background: var(--bn-canvas);
            color: var(--bn-body);
            font-family: BinanceNova, BinancePlex, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        h1 { font-size: clamp(2rem, 3vw, 3.7rem); line-height: 1.08; font-weight: 700; }
        h2 { font-size: clamp(1.5rem, 2vw, 2.1rem); line-height: 1.15; font-weight: 650; }
        h3 { font-size: clamp(1.1rem, 1.4vw, 1.45rem); line-height: 1.2; font-weight: 600; }

        p, li, td, th, label, input, select, textarea, button {
            font-size: 14px;
            line-height: 1.5;
            font-weight: 450;
        }

        a { color: inherit; text-decoration: none; }

        .eyebrow {
            margin: 0 0 0.55rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 12px;
            font-weight: 700;
            color: var(--bn-primary);
        }

        .eyebrow::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--bn-primary);
            flex: 0 0 8px;
        }

        .site-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background:
                radial-gradient(circle at 20% -20%, rgba(252, 213, 53, 0.08), transparent 35%),
                radial-gradient(circle at 90% 10%, rgba(59, 130, 246, 0.06), transparent 30%),
                var(--bn-canvas);
        }

        .top-shell {
            position: sticky;
            top: 0;
            z-index: 20;
            padding: 0.75rem 1rem 0;
            backdrop-filter: blur(8px);
        }

        .nav-pill {
            max-width: 1280px;
            margin: 0 auto;
            min-height: 60px;
            padding: 0.45rem 0.8rem;
            border-radius: var(--radius-pill);
            border: 1px solid var(--bn-line);
            background: rgba(11, 14, 17, 0.92);
            box-shadow: var(--shadow-soft);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
        }

        .brand-mark {
            width: 30px;
            height: 30px;
            border-radius: var(--radius-sm);
            background: var(--bn-primary);
            color: var(--bn-ink);
            display: inline-grid;
            place-items: center;
            font-size: 13px;
            font-weight: 900;
        }

        .brand-mark::before { content: "◆"; }

        .nav-links {
            flex: 1;
            display: flex;
            justify-content: center;
            gap: clamp(0.5rem, 2vw, 1.45rem);
            align-items: center;
            min-width: 0;
        }

        .nav-links a {
            font-size: 13px;
            font-weight: 600;
            padding: 0.4rem 0.1rem;
            border-bottom: 2px solid transparent;
            color: var(--bn-body);
            white-space: nowrap;
        }

        .nav-links a:hover,
        .nav-links a:focus-visible {
            color: #fff;
            border-bottom-color: var(--bn-primary);
            outline: none;
        }

        .nav-right {
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .circle-btn {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-pill);
            border: 1px solid var(--bn-line);
            background: var(--bn-card);
            color: var(--bn-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-nav { display: none; }

        .content {
            width: min(1280px, calc(100% - 2rem));
            margin: 1.3rem auto 0;
            padding-bottom: 3rem;
            flex: 1;
        }

        .hero {
            border-radius: var(--radius-lg);
            border: 1px solid var(--bn-line);
            background:
                linear-gradient(155deg, rgba(252, 213, 53, 0.12), rgba(252, 213, 53, 0.02)),
                var(--bn-card);
            color: var(--bn-body);
            padding: clamp(1.1rem, 2vw, 2.2rem);
            margin-bottom: 1rem;
            box-shadow: var(--shadow-soft);
        }

        .hero p { margin: 0.8rem 0 0; color: var(--bn-muted); max-width: 72ch; }

        .card {
            background: var(--bn-card);
            border: 1px solid var(--bn-line);
            border-radius: var(--radius-lg);
            padding: clamp(0.95rem, 1.8vw, 1.35rem);
            margin-bottom: 1rem;
        }

        .card-pill { border-radius: 14px; }

        .metric {
            font-size: clamp(1.8rem, 3.4vw, 2.8rem);
            line-height: 1.1;
            font-weight: 700;
            color: var(--bn-primary);
            margin: 0.4rem 0;
        }

        .muted { color: var(--bn-muted); margin: 0.35rem 0 0; }

        .grid { display: grid; gap: 1rem; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }

        .form-grid {
            display: grid;
            gap: 0.8rem;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        label {
            display: grid;
            gap: 0.35rem;
            color: #cdd1d6;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            background: #ffffff;
            color: var(--bn-ink);
            border: 1px solid #eaecef;
            border-radius: var(--radius-sm);
            padding: 0.58rem 0.82rem;
            outline: none;
            font-weight: 500;
        }

        textarea { min-height: 96px; resize: vertical; }

        input:focus, select:focus, textarea:focus {
            border-color: var(--bn-info);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .inline-check {
            display: inline-flex;
            width: auto;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .inline-check input { width: 16px; height: 16px; padding: 0; }

        .btn, button {
            min-height: 38px;
            border-radius: var(--radius-sm);
            border: 1px solid #4a5564;
            background: var(--bn-elevated);
            color: #fff;
            padding: 0.4rem 1rem;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.01em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            cursor: pointer;
            transition: background .15s ease;
        }

        .btn:hover, button:hover { background: #39414d; }

        .btn-main {
            background: var(--bn-primary);
            color: var(--bn-ink);
            border-color: var(--bn-primary);
        }

        .btn-main:hover { background: var(--bn-primary-active); }

        .btn-signal { background: var(--bn-up); border-color: var(--bn-up); color: #fff; }
        .btn-ghost { background: transparent; }
        .btn-sm { min-height: 32px; padding: 0.2rem 0.8rem; font-size: 13px; }

        .toolbar { display: flex; flex-wrap: wrap; gap: 0.55rem; align-items: center; }

        .table-wrap {
            overflow-x: auto;
            border-radius: var(--radius-md);
            border: 1px solid var(--bn-line);
            background: var(--bn-elevated);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 680px;
        }

        th, td {
            text-align: left;
            padding: 0.68rem 0.75rem;
            border-bottom: 1px solid #343b45;
            vertical-align: top;
            color: var(--bn-body);
        }

        th {
            background: #1a1f25;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 12px;
            font-weight: 700;
            color: #929aa5;
        }

        .status-pending { color: #f0b90b; font-weight: 700; }
        .status-approved { color: var(--bn-up); font-weight: 700; }
        .status-rejected { color: var(--bn-down); font-weight: 700; }

        .flash {
            border-radius: var(--radius-sm);
            padding: 0.7rem 0.9rem;
            margin-bottom: 0.75rem;
            border: 1px solid;
            font-weight: 600;
        }

        .flash-ok {
            background: rgba(14, 203, 129, 0.14);
            border-color: rgba(14, 203, 129, 0.5);
            color: #7de6bc;
        }

        .flash-err {
            background: rgba(246, 70, 93, 0.14);
            border-color: rgba(246, 70, 93, 0.48);
            color: #ff9aa8;
        }

        .footer {
            margin-top: 2rem;
            background: #000;
            color: #bbb;
            border-top: 1px solid #1f252d;
            padding: 2.6rem 1rem 1.8rem;
        }

        .footer-inner { width: min(1280px, 100%); margin: 0 auto; }

        .footer h2 {
            color: #fff;
            margin-bottom: 1.5rem;
            max-width: 26ch;
            font-size: clamp(1.35rem, 2vw, 2rem);
        }

        .footer-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 1.5rem;
        }

        .footer-col h4 {
            color: #929aa5;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.7rem;
            font-weight: 700;
        }

        .footer-col a {
            display: block;
            color: #ddd;
            margin: 0.32rem 0;
            font-size: 13px;
        }

        .footer-bottom {
            border-top: 1px solid #1f252d;
            padding-top: 0.85rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.6rem;
            color: #929aa5;
            font-size: 13px;
        }

        @media (max-width: 1023px) {
            .nav-links { display: none; }
            .mobile-nav { display: block; margin-left: auto; }

            .mobile-nav summary {
                list-style: none;
                width: 38px;
                height: 38px;
                border-radius: var(--radius-sm);
                border: 1px solid var(--bn-line);
                background: var(--bn-card);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: var(--bn-primary);
                cursor: pointer;
            }

            .mobile-panel {
                margin-top: 0.5rem;
                background: var(--bn-card);
                border: 1px solid var(--bn-line);
                border-radius: var(--radius-md);
                padding: 0.55rem;
                display: grid;
                gap: 0.35rem;
            }

            .mobile-panel a {
                padding: 0.5rem 0.65rem;
                border-radius: var(--radius-xs);
                border: 1px solid transparent;
            }

            .mobile-panel a:hover {
                border-color: #3a424d;
                background: #252b33;
            }

            .grid-3 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 767px) {
            .content { width: calc(100% - 1rem); margin-top: 0.95rem; }
            .top-shell { padding: 0.65rem 0.5rem 0; }
            .nav-pill { padding: 0.4rem 0.5rem; }
            .brand span:last-child { display: none; }

            .grid-2,
            .grid-3,
            .form-grid { grid-template-columns: 1fr; }

            .footer-grid { grid-template-columns: 1fr; }
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
                    <span>Binance-Style Back Office</span>
                </a>

                <nav class="nav-links">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    @if(auth()->user()->isOwner())
                        <a href="{{ route('owner.staff.index') }}">Staff</a>
                        <a href="{{ route('owner.kpi.index') }}">KPI</a>
                        <a href="{{ route('owner.menu-variants.index') }}">Menu</a>
                        <a href="{{ route('owner.validations.index') }}">Validasi</a>
                        <a href="{{ route('owner.finance') }}">Finance</a>
                        <a href="{{ route('owner.sales-analytics') }}">Sales Insight</a>
                        <a href="{{ route('owner.payroll.index') }}">Payroll</a>
                    @else
                        <a href="{{ route('staff.attendance') }}">Presensi</a>
                        <a href="{{ route('staff.shifts') }}">Shift</a>
                        <a href="{{ route('staff.expenses') }}">Expense</a>
                        <a href="{{ route('staff.kpi') }}">KPI Harian</a>
                        <a href="{{ route('staff.store-leader') }}">Store Leader</a>
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
                            <a href="{{ route('owner.menu-variants.index') }}">Menu</a>
                            <a href="{{ route('owner.validations.index') }}">Validasi</a>
                            <a href="{{ route('owner.finance') }}">Finance</a>
                            <a href="{{ route('owner.sales-analytics') }}">Sales Insight</a>
                            <a href="{{ route('owner.payroll.index') }}">Payroll</a>
                        @else
                            <a href="{{ route('staff.attendance') }}">Presensi</a>
                            <a href="{{ route('staff.shifts') }}">Shift</a>
                            <a href="{{ route('staff.expenses') }}">Expense</a>
                            <a href="{{ route('staff.kpi') }}">KPI Harian</a>
                            <a href="{{ route('staff.store-leader') }}">Store Leader</a>
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
            <h2>One dashboard to track operations, revenue, and team performance with confidence.</h2>

            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Platform</h4>
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('login') }}">Akses Akun</a>
                </div>
                <div class="footer-col">
                    <h4>Operations</h4>
                    <a href="#">Presensi</a>
                    <a href="#">Shift Recon</a>
                    <a href="#">KPI Harian</a>
                </div>
                <div class="footer-col">
                    <h4>Finance</h4>
                    <a href="#">Sales Insight</a>
                    <a href="#">Expense & Nota</a>
                    <a href="#">Payroll</a>
                </div>
                <div class="footer-col">
                    <h4>Support</h4>
                    <a href="#">Panduan Implementasi</a>
                    <a href="#">Kebijakan Data</a>
                    <a href="#">Dukungan Sistem</a>
                </div>
            </div>

            <div class="footer-bottom">
                <span>© {{ now()->year }} Binance-Style Back Office</span>
                <span>Design source: binance/DESIGN.md</span>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
