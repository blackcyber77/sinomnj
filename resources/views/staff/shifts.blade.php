@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Shift Report</p>
    <h1>Rekapitulasi penjualan per shift dan rekonsiliasi kas.</h1>
    <p>Catat opening cash, penjualan multichannel, lalu bandingkan expected vs actual cash.</p>
</section>
@endsection

@section('content')
<div class="card">
    <p class="eyebrow">Input Shift</p>
    <h2>Rekap Shift</h2>
    <form method="post" action="{{ route('staff.shifts.store') }}" class="form-grid">
        @csrf
        <label>Tanggal<input type="date" name="report_date" required></label>
        <label>Nama Shift<input name="shift_name" placeholder="Pagi/Malam" required></label>
        <label>Opening Cash<input type="number" name="opening_cash" min="0" step="1000" required></label>
        <label>Cash Sales<input type="number" name="cash_sales" min="0" step="1000" required></label>
        <label>QRIS Sales<input type="number" name="qris_sales" min="0" step="1000" required></label>
        <label>Merchant Sales<input type="number" name="merchant_sales" min="0" step="1000" required></label>
        <label>Closing Cash Aktual<input type="number" name="closing_cash_actual" min="0" step="1000" required></label>
        <label>Catatan<textarea name="notes"></textarea></label>
        <div class="toolbar"><button class="btn btn-main" type="submit">Simpan Rekap</button></div>
    </form>
</div>

<div class="card">
    <p class="eyebrow">Submitted Reports</p>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tanggal</th><th>Shift</th><th>Total Revenue</th><th>Expected Cash</th><th>Actual Cash</th></tr></thead>
            <tbody>
            @foreach($reports as $r)
                <tr>
                    <td>{{ $r->report_date->format('Y-m-d') }}</td>
                    <td>{{ $r->shift_name }}</td>
                    <td>{{ number_format($r->total_revenue, 0, ',', '.') }}</td>
                    <td>{{ number_format($r->cash_expected, 0, ',', '.') }}</td>
                    <td>{{ number_format($r->closing_cash_actual, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
