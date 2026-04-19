@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Attendance Flow</p>
    <h1>Check-in dan check-out harian berbasis shift.</h1>
    <p>Data presensi menjadi fondasi perhitungan payroll periodik.</p>
</section>
@endsection

@section('content')
<div class="card card-pill">
    <p class="eyebrow">Today</p>
    <h2>Presensi</h2>
    <p class="muted">Tanggal: {{ now()->format('Y-m-d') }}</p>
    <div class="grid grid-2" style="margin-top: .9rem;">
        <div>
            <h3>Check-in</h3>
            <div class="metric">{{ $today?->check_in_at?->format('H:i:s') ?? '-' }}</div>
        </div>
        <div>
            <h3>Check-out</h3>
            <div class="metric">{{ $today?->check_out_at?->format('H:i:s') ?? '-' }}</div>
        </div>
    </div>

    <div class="toolbar" style="margin-top: 1rem;">
        <form method="post" action="{{ route('staff.attendance.checkin') }}">@csrf <button class="btn btn-main" type="submit">Check-In</button></form>
        <form method="post" action="{{ route('staff.attendance.checkout') }}">@csrf <button class="btn" type="submit">Check-Out</button></form>
    </div>
</div>
@endsection
