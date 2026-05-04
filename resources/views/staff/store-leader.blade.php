@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Store Leader Input</p>
    <h1>Isi jumlah laku menu berdasarkan varian dan tanggal laporan.</h1>
    <p>Kamu bisa pilih tanggal mundur jika lupa input hari sebelumnya, lalu isi kuantitas laku tiap menu.</p>
</section>
@endsection

@section('content')
<div class="card card-pill">
    <p class="eyebrow">Pilih Tanggal</p>
    <form method="get" class="toolbar">
        <label style="max-width: 260px;">
            Tanggal laporan
            <input type="date" name="date" value="{{ $selectedDate }}">
        </label>
        <button class="btn" type="submit">Muat Data</button>
    </form>
</div>

<div class="card">
    <p class="eyebrow">Input Quantity Menu</p>
    <h2>Jumlah Laku Per Varian</h2>

    @if($menuVariants->isEmpty())
        <p class="muted">Belum ada varian menu aktif. Minta owner menambahkan varian menu terlebih dahulu.</p>
    @else
        <form method="post" action="{{ route('staff.store-leader.quantities') }}" class="grid" style="gap: 1rem;">
            @csrf
            <input type="hidden" name="report_date" value="{{ $selectedDate }}">

            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Varian Menu</th>
                        <th>Qty Laku</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($menuVariants as $variant)
                        <tr>
                            <td>{{ $variant->name }}</td>
                            <td style="width: 180px;">
                                <input
                                    type="number"
                                    min="0"
                                    name="quantities[{{ $variant->id }}]"
                                    value="{{ old('quantities.'.$variant->id, $quantitiesByVariant[$variant->id] ?? 0) }}"
                                >
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="toolbar">
                <button class="btn btn-main" type="submit">Simpan Quantity</button>
            </div>
        </form>
    @endif
</div>

@if($summary)
    @php
        $totalQty = (int) $summary->items->sum('quantity');
    @endphp

    <div class="grid grid-2">
        <article class="card card-pill">
            <p class="eyebrow">Ringkasan</p>
            <h3>Total Produk Laku</h3>
            <div class="metric">{{ $totalQty }}</div>
            <p class="muted">Akumulasi quantity untuk tanggal {{ $selectedDate }}.</p>
        </article>

        <article class="card card-pill">
            <p class="eyebrow">Tanggal</p>
            <h3>Laporan Aktif</h3>
            <div class="metric">{{ \Illuminate\Support\Carbon::parse($selectedDate)->format('d') }}</div>
            <p class="muted">{{ \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('F Y') }}</p>
        </article>
    </div>

    <div class="card">
        <p class="eyebrow">Data Tersimpan</p>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Varian Menu</th><th>Qty</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($summary->items as $item)
                    <tr>
                        <td>{{ $item->menuVariant->name ?? $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            <form method="post" action="{{ route('staff.store-leader.items.delete', $item) }}" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="muted">Belum ada item tersimpan untuk tanggal ini.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
