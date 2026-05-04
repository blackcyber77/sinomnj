@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Menu Master</p>
    <h1>Owner mengelola varian menu untuk input Store Leader.</h1>
    <p>Store Leader tidak perlu mengetik nama produk manual lagi, cukup isi jumlah laku per varian per tanggal.</p>
</section>
@endsection

@section('content')
<div class="card">
    <p class="eyebrow">Tambah Varian</p>
    <h2>Master Varian Menu</h2>

    <form method="post" action="{{ route('owner.menu-variants.store') }}" class="form-grid">
        @csrf
        <label>Nama Varian Menu
            <input name="name" placeholder="Contoh: Es Kopi Susu Gula Aren" required>
        </label>

        <label class="inline-check" style="align-self: end;">
            <input type="checkbox" name="is_active" value="1" checked>
            Aktif digunakan Store Leader
        </label>

        <div class="toolbar">
            <button class="btn btn-main" type="submit">Tambah Varian</button>
        </div>
    </form>
</div>

<div class="card">
    <p class="eyebrow">Daftar Varian</p>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Varian Menu</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($variants as $variant)
                <tr>
                    <td>{{ $variant->name }}</td>
                    <td>{{ $variant->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                    <td>
                        <form method="post" action="{{ route('owner.menu-variants.delete', $variant) }}" onsubmit="return confirm('Hapus varian menu ini?')">
                            @csrf
                            @method('delete')
                            <button class="btn btn-sm" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="muted">Belum ada varian menu.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
