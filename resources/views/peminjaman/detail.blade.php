@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">PEMINJAMAN</p>
    <h2>Detail Peminjaman #{{ $peminjaman->id }}</h2>
</div>

<div class="detail-card">
    <div class="detail-content">

        <span class="badge badge-{{ $peminjaman->status }}">{{ ucfirst($peminjaman->status) }}</span>

        <div class="detail-info">
            <p><strong>Anggota</strong><span>{{ $peminjaman->anggota->nama ?? '-' }}</span></p>
            <p><strong>Email</strong><span>{{ $peminjaman->anggota->email ?? '-' }}</span></p>
            <p><strong>Buku</strong><span>{{ $peminjaman->buku->judul ?? '-' }}</span></p>
            <p><strong>Tanggal Pinjam</strong><span>{{ $peminjaman->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</span></p>
            <p><strong>Tanggal Kembali</strong><span>{{ $peminjaman->tanggal_kembali?->format('d/m/Y') ?? '-' }}</span></p>
        </div>

        <a href="{{ route('peminjaman.saya') }}" class="btn">Kembali</a>
    </div>
</div>

@endsection