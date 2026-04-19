@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Owner Control Tower</p>
    <h1>Satu panel untuk audit operasional coffee shop.</h1>
    <p>Lihat status tim, pemasukan harian, dan item validasi dalam satu tampilan seperti editorial cockpit.</p>
</section>
@endsection

@section('content')
<div class="grid grid-2">
    <article class="card card-pill">
        <p class="eyebrow">Team</p>
        <h3>Total Staff</h3>
        <div class="metric">{{ $staffCount }}</div>
        <p class="muted">Jumlah staff aktif dalam sistem.</p>
    </article>

    <article class="card card-pill">
        <p class="eyebrow">Attendance</p>
        <h3>Presensi Hari Ini</h3>
        <div class="metric">{{ $todayAttendance }}</div>
        <p class="muted">Absensi check-in yang sudah tercatat.</p>
    </article>

    <article class="card card-pill">
        <p class="eyebrow">Revenue</p>
        <h3>Total Revenue Hari Ini</h3>
        <div class="metric">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
        <p class="muted">Gabungan Cash, QRIS, dan Merchant.</p>
    </article>

    <article class="card card-pill">
        <p class="eyebrow">Approval Queue</p>
        <h3>Menunggu Validasi</h3>
        <div class="metric">{{ $pendingExpenses + $pendingKpi }}</div>
        <p class="muted">Expense: {{ $pendingExpenses }} • KPI: {{ $pendingKpi }}</p>
    </article>
</div>
@endsection
