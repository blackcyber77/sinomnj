@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Performance Variables</p>
    <h1>Tentukan indikator KPI dan bobot yang objektif.</h1>
    <p>Variabel aktif otomatis muncul di self-assessment staff dan dipakai untuk insentif payroll.</p>
</section>
@endsection

@section('content')
<div class="card">
    <p class="eyebrow">KPI Configuration</p>
    <h2>Konfigurasi KPI</h2>
    <form method="post" action="{{ route('owner.kpi.store') }}" class="form-grid">
        @csrf
        <label>Nama Variabel<input name="name" required></label>
        <label>Bobot<input type="number" name="weight" min="1" max="100" required></label>
        <label class="inline-check"><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
        <div class="toolbar"><button class="btn btn-main" type="submit">Tambah KPI</button></div>
    </form>
</div>

<div class="card">
    <p class="eyebrow">Variable List</p>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Variabel</th><th>Bobot</th><th>Status</th></tr></thead>
            <tbody>
            @foreach($variables as $v)
                <tr><td>{{ $v->name }}</td><td>{{ $v->weight }}</td><td>{{ $v->is_active ? 'Aktif' : 'Nonaktif' }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
