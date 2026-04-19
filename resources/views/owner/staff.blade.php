@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">People Management</p>
    <h1>Kelola data staff dan rate gaji harian secara terpusat.</h1>
    <p>Perubahan profil dan kompensasi tersimpan rapi untuk kalkulasi payroll otomatis.</p>
</section>
@endsection

@section('content')
<div class="card">
    <p class="eyebrow">Create Staff</p>
    <h2>Manajemen Staff</h2>
    <form method="post" action="{{ route('owner.staff.store') }}" class="form-grid">
        @csrf
        <label>Nama<input name="name" required></label>
        <label>Email<input type="email" name="email" required></label>
        <label>Password Awal<input type="password" name="password" required></label>
        <label>Rate Gaji Harian<input type="number" name="daily_rate" min="0" step="1000" required></label>
        <label class="inline-check"><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
        <div class="toolbar"><button class="btn btn-main" type="submit">Tambah Staff</button></div>
    </form>
</div>

<div class="card">
    <p class="eyebrow">Roster</p>
    <h3>Daftar Staff</h3>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nama</th><th>Email</th><th>Rate</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($staffs as $staff)
                <tr>
                    <td>{{ $staff->name }}</td>
                    <td>{{ $staff->email }}</td>
                    <td>Rp {{ number_format($staff->daily_rate, 0, ',', '.') }}</td>
                    <td>{{ $staff->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                    <td>
                        <form method="post" action="{{ route('owner.staff.delete', $staff) }}" onsubmit="return confirm('Hapus staff ini?')">
                            @csrf @method('delete')
                            <button class="btn btn-sm" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
