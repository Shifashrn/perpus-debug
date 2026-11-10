@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">ADMINISTRATOR</p>
    <h2>Dashboard Admin</h2>
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
    @can('kelola-buku')
        <a href="{{ route('buku.kelola') }}" class="btn">Kelola Buku</a>
    @endcan
    @can('kelola data pengguna')
        <a href="{{ route('admin.pengguna.index') }}" class="btn">Kelola Pengguna & Role</a>
    @endcan
    @can('kelola peminjaman')
        <a href="{{ route('peminjaman.index') }}" class="btn">Data Peminjaman</a>
    @endcan
    @can('kelola pengembalian')
        <a href="{{ route('pengembalian.index') }}" class="btn">Data Pengembalian</a>
    @endcan
    @can('lihat laporan perpustakaan')
        <a href="{{ route('laporan.index') }}" class="btn">Laporan</a>
    @endcan
</div>

@endsection