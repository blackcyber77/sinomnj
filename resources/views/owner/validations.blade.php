@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Approval Center</p>
    <h1>Validasi nota pengeluaran dan laporan KPI staff.</h1>
    <p>Seluruh keputusan approval tercatat sebagai audit trail untuk transparansi operasional.</p>
</section>
@endsection

@section('content')
<div class="card">
    <p class="eyebrow">Expense Validation</p>
    <h2>Validasi Pengeluaran</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tanggal</th><th>Staff</th><th>Kategori</th><th>Amount</th><th>Nota</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                    <td>{{ $expense->user->name }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td>
                        @if($expense->receipt_path)
                            <a class="btn btn-sm" href="{{ asset('storage/'.$expense->receipt_path) }}" target="_blank">Lihat</a>
                        @endif
                    </td>
                    <td>
                        <form method="post" action="{{ route('owner.validations.expense', $expense) }}" class="grid">
                            @csrf @method('patch')
                            <select name="status"><option value="approved">Approve</option><option value="rejected">Reject</option></select>
                            <input name="validation_note" placeholder="Catatan validasi (opsional)">
                            <button class="btn btn-sm btn-main" type="submit">Simpan</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <p class="eyebrow">KPI Validation</p>
    <h2>Validasi KPI</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tanggal</th><th>Staff</th><th>Variabel</th><th>Score</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($kpiEntries as $entry)
                <tr>
                    <td>{{ $entry->entry_date->format('Y-m-d') }}</td>
                    <td>{{ $entry->user->name }}</td>
                    <td>{{ $entry->variable->name }}</td>
                    <td>{{ $entry->score }}</td>
                    <td>
                        <form method="post" action="{{ route('owner.validations.kpi', $entry) }}" class="toolbar">
                            @csrf @method('patch')
                            <select name="status"><option value="approved">Approve</option><option value="rejected">Reject</option></select>
                            <button class="btn btn-sm btn-main" type="submit">Simpan</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
