@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">LAPORAN</p>
    <h2>Laporan Perpustakaan</h2>
    <p>Ringkasan koleksi dan aktivitas peminjaman.</p>
</div>

<div class="stat-grid">
    @foreach ($ringkasan as $label => $nilai)
        <div class="stat-card">
            <div class="angka">{{ $nilai }}</div>
            <div class="label">{{ $label }}</div>
        </div>
    @endforeach
</div>

<h3 class="section-title">Peminjaman per Status</h3>
<div class="table-wrap" style="margin-top:0;">
    <table class="data-table">
        <thead>
            <tr><th>Status</th><th>Jumlah</th></tr>
        </thead>
        <tbody>
            @foreach (['diajukan', 'dipinjam', 'dikembalikan', 'ditolak'] as $status)
                <tr>
                    <td><span class="badge badge-{{ $status }}">{{ ucfirst($status) }}</span></td>
                    <td>{{ $perStatus[$status] ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h3 class="section-title">Jumlah Buku per Kategori</h3>
<div class="table-wrap" style="margin-top:0;">
    <table class="data-table">
        <thead>
            <tr><th>Kategori</th><th>Jumlah Buku</th></tr>
        </thead>
        <tbody>
            @forelse ($perKategori as $k)
                <tr>
                    <td>{{ $k->nama }}</td>
                    <td>{{ $k->bukus_count }}</td>
                </tr>
            @empty
                <tr><td colspan="2" style="text-align:center;">Belum ada kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3 class="section-title">Buku Paling Sering Dipinjam</h3>
<div class="table-wrap" style="margin-top:0;">
    <table class="data-table">
        <thead>
            <tr><th>Judul</th><th>Kategori</th><th>Dipinjam</th></tr>
        </thead>
        <tbody>
            @forelse ($bukuPopuler as $b)
                <tr>
                    <td>{{ $b->judul }}</td>
                    <td>{{ $b->kategori->nama ?? '-' }}</td>
                    <td>{{ $b->peminjamans_count }}x</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align:center;">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection