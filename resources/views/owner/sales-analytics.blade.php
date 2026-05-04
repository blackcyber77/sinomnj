@extends('layouts.app')

@section('hero')
<section class="hero">
    <p class="eyebrow">Owner Sales Analytics</p>
    <h1>Grafik penjualan fleksibel dengan insight omset dan produk laku.</h1>
    <p>Pilih metrik, ubah grouping (hari/minggu/bulan), dan analisis rata-rata quantity produk untuk periode custom.</p>
</section>
@endsection

@section('content')
<div class="card">
    <p class="eyebrow">Filter & Pilihan Grafik</p>
    <h2>Grafik Penjualan</h2>

    <form method="get" class="grid" style="gap: 1rem;">
        <div class="form-grid">
            <label>Dari Tanggal
                <input type="date" name="date_from" value="{{ $dateFrom }}" required>
            </label>

            <label>Sampai Tanggal
                <input type="date" name="date_to" value="{{ $dateTo }}" required>
            </label>

            <label>Grouping Waktu
                <select name="group_by">
                    <option value="day" @selected($groupBy === 'day')>Per Hari</option>
                    <option value="week" @selected($groupBy === 'week')>Per Minggu</option>
                    <option value="month" @selected($groupBy === 'month')>Per Bulan</option>
                </select>
            </label>
        </div>

        <div>
            <p class="eyebrow">Metrik Ditampilkan</p>
            <div class="grid grid-3">
                @foreach($availableMetrics as $key => $metric)
                    <label class="inline-check">
                        <input type="checkbox" name="metrics[]" value="{{ $key }}" @checked(in_array($key, $selectedMetrics, true))>
                        {{ $metric['label'] }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="toolbar">
            <button class="btn btn-main" type="submit">Terapkan Filter</button>
        </div>
    </form>
</div>

<div class="grid grid-2">
    <article class="card card-pill">
        <p class="eyebrow">AVG Produk Laku</p>
        <h3>Per Hari</h3>
        <div class="metric">{{ number_format($avgStats['avg_day'], 2, ',', '.') }}</div>
    </article>

    <article class="card card-pill">
        <p class="eyebrow">AVG Produk Laku</p>
        <h3>Per Minggu</h3>
        <div class="metric">{{ number_format($avgStats['avg_week'], 2, ',', '.') }}</div>
    </article>

    <article class="card card-pill">
        <p class="eyebrow">AVG Produk Laku</p>
        <h3>Per Bulan</h3>
        <div class="metric">{{ number_format($avgStats['avg_month'], 2, ',', '.') }}</div>
    </article>

    <article class="card card-pill">
        <p class="eyebrow">AVG Produk Laku</p>
        <h3>Custom Range</h3>
        <div class="metric">{{ number_format($avgStats['avg_custom_range'], 2, ',', '.') }}</div>
    </article>
</div>

<div class="card">
    <p class="eyebrow">Sales Chart</p>
    <canvas id="salesChart" height="120"></canvas>
    <p class="muted" style="margin-top: .8rem;">Grafik menyesuaikan filter tanggal, grouping, dan metrik yang dipilih.</p>
</div>

<div class="card">
    <p class="eyebrow">Top Produk Laku</p>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Produk</th><th>Qty</th><th>Omset</th></tr></thead>
            <tbody>
            @forelse($topProducts as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['qty'] }}</td>
                    <td>Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="muted">Belum ada data produk pada range ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <p class="eyebrow">Detail Data Penjualan</p>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Periode</th>
                <th>Total Revenue</th>
                <th>Walk In</th>
                <th>Merchant</th>
                <th>Qty Total</th>
                <th>Qty Merchant</th>
            </tr>
            </thead>
            <tbody>
            @forelse($points as $point)
                <tr>
                    <td>{{ $point['label'] }}</td>
                    <td>Rp {{ number_format($point['total_revenue'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($point['walk_in_revenue'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($point['merchant_revenue'], 0, ',', '.') }}</td>
                    <td>{{ number_format($point['total_qty'], 0, ',', '.') }}</td>
                    <td>{{ number_format($point['merchant_qty'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">Belum ada data pada range yang dipilih.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const payload = @json($chartPayload);
    const ctx = document.getElementById('salesChart');

    if (ctx && payload) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: payload.labels,
                datasets: payload.datasets,
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => `${context.dataset.label}: ${new Intl.NumberFormat('id-ID').format(context.parsed.y)}`,
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(20, 20, 19, 0.08)',
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(20, 20, 19, 0.04)',
                        }
                    }
                }
            }
        });
    }
</script>
@endsection
