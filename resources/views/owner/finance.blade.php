@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Finance Audit</p>
    <h1>Pelacakan pemasukan multichannel dan rekonsiliasi kas shift.</h1>
    <p>Filter data berdasarkan tanggal dan shift untuk memverifikasi saldo operasional secara detail.</p>
</section>
@endsection

@section('content')
<div class="card">
    <p class="eyebrow">Filter Report</p>
    <h2>Laporan Keuangan</h2>
    <form method="get" class="form-grid">
        <label>Dari Tanggal<input type="date" name="date_from" value="{{ $dateFrom }}"></label>
        <label>Sampai Tanggal<input type="date" name="date_to" value="{{ $dateTo }}"></label>
        <label>Shift (opsional)<input name="shift_name" value="{{ $shiftName }}" placeholder="pagi/malam"></label>
        <div class="toolbar"><button class="btn btn-main" type="submit">Filter</button></div>
    </form>
</div>

<div class="grid grid-2">
    <article class="card card-pill"><p class="eyebrow">Cash</p><div class="metric">Rp {{ number_format($totals['cash'], 0, ',', '.') }}</div></article>
    <article class="card card-pill"><p class="eyebrow">QRIS</p><div class="metric">Rp {{ number_format($totals['qris'], 0, ',', '.') }}</div></article>
    <article class="card card-pill"><p class="eyebrow">Merchant</p><div class="metric">Rp {{ number_format($totals['merchant'], 0, ',', '.') }}</div></article>
    <article class="card card-pill"><p class="eyebrow">Expense</p><div class="metric">Rp {{ number_format($totals['expenses'], 0, ',', '.') }}</div></article>
</div>

<div class="card">
    <p class="eyebrow">Shift Reconciliation</p>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tanggal</th><th>Shift</th><th>Staff</th><th>Cash</th><th>QRIS</th><th>Merchant</th><th>Expected Cash</th><th>Actual Cash</th></tr></thead>
            <tbody>
            @foreach($reports as $r)
                <tr>
                    <td>{{ $r->report_date->format('Y-m-d') }}</td>
                    <td>{{ $r->shift_name }}</td>
                    <td>{{ $r->user->name }}</td>
                    <td>{{ number_format($r->cash_sales, 0, ',', '.') }}</td>
                    <td>{{ number_format($r->qris_sales, 0, ',', '.') }}</td>
                    <td>{{ number_format($r->merchant_sales, 0, ',', '.') }}</td>
                    <td>{{ number_format($r->cash_expected, 0, ',', '.') }}</td>
                    <td>{{ number_format($r->closing_cash_actual, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
