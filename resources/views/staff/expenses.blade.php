@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Petty Cash</p>
    <h1>Input pengeluaran operasional dengan bukti nota digital.</h1>
    <p>Setiap item otomatis masuk antrean validasi owner agar saldo kas tetap transparan.</p>
</section>
@endsection

@section('content')
<div class="card">
    <p class="eyebrow">Submit Expense</p>
    <h2>Input Pengeluaran + Upload Nota</h2>
    <form method="post" action="{{ route('staff.expenses.store') }}" enctype="multipart/form-data" class="form-grid">
        @csrf
        <label>Tanggal<input type="date" name="expense_date" required></label>
        <label>Shift Terkait
            <select name="shift_report_id">
                <option value="">- Tidak terkait shift -</option>
                @foreach($reports as $r)
                    <option value="{{ $r->id }}">{{ $r->report_date->format('Y-m-d') }} - {{ $r->shift_name }}</option>
                @endforeach
            </select>
        </label>
        <label>Kategori<input name="category" placeholder="Restock/Darurat" required></label>
        <label>Jumlah<input type="number" name="amount" min="1" step="1000" required></label>
        <label>Nota<input type="file" name="receipt" accept="image/*" required></label>
        <label>Deskripsi<textarea name="description"></textarea></label>
        <div class="toolbar"><button class="btn btn-main" type="submit">Kirim Pengeluaran</button></div>
    </form>
</div>

<div class="card">
    <p class="eyebrow">Expense History</p>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tanggal</th><th>Kategori</th><th>Amount</th><th>Status</th><th>Nota</th></tr></thead>
            <tbody>
            @foreach($expenses as $e)
                <tr>
                    <td>{{ $e->expense_date->format('Y-m-d') }}</td>
                    <td>{{ $e->category }}</td>
                    <td>{{ number_format($e->amount, 0, ',', '.') }}</td>
                    <td class="status-{{ $e->status }}">{{ strtoupper($e->status) }}</td>
                    <td>@if($e->receipt_path)<a class="btn btn-sm" href="{{ asset('storage/'.$e->receipt_path) }}" target="_blank">Lihat</a>@endif</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
