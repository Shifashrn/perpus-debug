@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">PETUGAS</p>
    <h2>Dashboard Petugas</h2>
    <p>Selamat datang, {{ auth()->user()->name }}.</p>
</div>

<div class="stat-grid">
    @foreach ($statistik as $label => $nilai)
        <div class="stat-card">
            <div class="angka">{{ $nilai }}</div>
            <div class="label">{{ $label }}</div>
        </div>
    @endforeach
</div>

<h3 class="section-title">Menu Cepat</h3>
<div class="aksi-grup">
    @can('kelola data buku')
        <a href="{{ route('buku.kelola') }}" class="btn">Kelola Buku</a>
    @endcan
    @can('kelola peminjaman')
        <a href="{{ route('peminjaman.index') }}" class="btn">Proses Peminjaman</a>
    @endcan
    @can('kelola pengembalian')
        <a href="{{ route('pengembalian.index') }}" class="btn">Catat Pengembalian</a>
    @endcan
    @can('lihat laporan perpustakaan')
        <a href="{{ route('laporan.index') }}" class="btn">Laporan</a>
    @endcan
</div>

@endsection