@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Daily Performance</p>
    <h1>Self-assessment KPI harian dengan variabel berbobot.</h1>
    <p>Masukkan skor per variabel untuk dasar evaluasi performa dan bonus periode payroll.</p>
</section>
@endsection

@section('content')
<div class="card">
    <p class="eyebrow">Submit KPI</p>
    <h2>Self-Assessment KPI Harian</h2>
    <form method="post" action="{{ route('staff.kpi.store') }}" class="form-grid">
        @csrf
        <label>Tanggal<input type="date" name="entry_date" required></label>
        <label>Variabel KPI
            <select name="kpi_variable_id" required>
                <option value="">Pilih variabel</option>
                @foreach($variables as $v)
                    <option value="{{ $v->id }}">{{ $v->name }} (bobot {{ $v->weight }})</option>
                @endforeach
            </select>
        </label>
        <label>Skor (0-100)<input type="number" name="score" min="0" max="100" required></label>
        <label>Catatan<textarea name="notes"></textarea></label>
        <div class="toolbar"><button class="btn btn-main" type="submit">Kirim KPI</button></div>
    </form>
</div>

<div class="card">
    <p class="eyebrow">KPI History</p>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tanggal</th><th>Variabel</th><th>Skor</th><th>Status</th><th>Catatan</th></tr></thead>
            <tbody>
            @foreach($entries as $e)
                <tr>
                    <td>{{ $e->entry_date->format('Y-m-d') }}</td>
                    <td>{{ $e->variable->name }}</td>
                    <td>{{ $e->score }}</td>
                    <td class="status-{{ $e->status }}">{{ strtoupper($e->status) }}</td>
                    <td>{{ $e->notes }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
