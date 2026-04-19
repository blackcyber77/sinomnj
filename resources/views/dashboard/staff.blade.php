@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Staff Workspace</p>
    <h1>Input cepat untuk shift, kas kecil, dan KPI harian.</h1>
    <p>Semua transaksi operasional dicatat real-time agar owner bisa audit dan payroll berjalan otomatis.</p>
</section>
@endsection

@section('content')
<div class="grid grid-2">
    <article class="card card-pill">
        <p class="eyebrow">Presensi</p>
        <h3>Status Hari Ini</h3>
        @if($todayAttendance)
            <div class="metric">{{ $todayAttendance->check_in_at?->format('H:i') ?? '-' }}</div>
            <p class="muted">Check-in {{ $todayAttendance->check_in_at?->format('H:i') ?? '-' }} • Check-out {{ $todayAttendance->check_out_at?->format('H:i') ?? '-' }}</p>
        @else
            <div class="metric">-</div>
            <p class="muted">Belum ada check-in hari ini.</p>
        @endif
    </article>

    <article class="card card-pill">
        <p class="eyebrow">Shift</p>
        <h3>Rekap Minggu Ini</h3>
        <div class="metric">{{ $weekShifts }}</div>
        <p class="muted">Jumlah laporan shift yang sudah dikirim.</p>
    </article>

    <article class="card card-pill">
        <p class="eyebrow">Expense</p>
        <h3>Pengeluaran Pending</h3>
        <div class="metric">{{ $pendingExpenses }}</div>
        <p class="muted">Menunggu review owner.</p>
    </article>

    <article class="card card-pill">
        <p class="eyebrow">KPI</p>
        <h3>KPI Pending</h3>
        <div class="metric">{{ $pendingKpi }}</div>
        <p class="muted">Belum tervalidasi owner.</p>
    </article>
</div>
@endsection
