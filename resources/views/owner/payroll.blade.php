@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Payroll Engine</p>
    <h1>Hitung gaji otomatis dari absensi, rate personal, dan skor KPI.</h1>
    <p>Sistem memberi bonus ketika skor KPI melewati threshold yang ditentukan owner.</p>
</section>
@endsection

@section('content')
<div class="card">
    <p class="eyebrow">Generate Period</p>
    <h2>Generate Payroll</h2>
    <form method="post" action="{{ route('owner.payroll.generate') }}" class="form-grid">
        @csrf
        <label>Start Date<input type="date" name="start_date" required></label>
        <label>End Date<input type="date" name="end_date" required></label>
        <label>Bonus Threshold (Skor KPI)<input type="number" name="bonus_threshold" value="80" required></label>
        <label>Bonus Amount<input type="number" name="bonus_amount" value="50000" min="0" step="1000" required></label>
        <div class="toolbar"><button class="btn btn-main" type="submit">Proses Payroll</button></div>
    </form>
</div>

@foreach($periods as $period)
<div class="card">
    <p class="eyebrow">Payroll Result</p>
    <h3>Periode {{ $period->start_date->format('Y-m-d') }} s/d {{ $period->end_date->format('Y-m-d') }}</h3>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Staff</th><th>Hari Masuk</th><th>Rate</th><th>Gaji Pokok</th><th>Skor KPI</th><th>Bonus</th><th>Total</th></tr></thead>
            <tbody>
            @foreach($period->payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->user->name }}</td>
                    <td>{{ $payroll->attendance_days }}</td>
                    <td>{{ number_format($payroll->daily_rate, 0, ',', '.') }}</td>
                    <td>{{ number_format($payroll->base_salary, 0, ',', '.') }}</td>
                    <td>{{ $payroll->kpi_score }}</td>
                    <td>{{ number_format($payroll->bonus_amount, 0, ',', '.') }}</td>
                    <td>{{ number_format($payroll->total_salary, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endsection
