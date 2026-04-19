@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Integrated Coffee Ops</p>
    <h1>Back-office terpusat untuk operasional, keuangan, dan performa tim.</h1>
    <p>Masuk sebagai owner atau staff untuk mengelola presensi, multichannel revenue, validasi nota, KPI harian, dan payroll otomatis.</p>
</section>
@endsection

@section('content')
<div class="card" style="max-width: 520px; margin: 0 auto;">
    <p class="eyebrow">Account Access</p>
    <h2>Login Sistem</h2>
    <p class="muted">Gunakan akun yang sudah terdaftar pada sistem.</p>

    <form method="post" action="{{ route('login.attempt') }}" class="grid">
        @csrf
        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <label class="inline-check"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
        <div class="toolbar">
            <button class="btn btn-main" type="submit">Login</button>
        </div>
    </form>
</div>
@endsection
