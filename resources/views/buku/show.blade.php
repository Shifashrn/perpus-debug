@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">INFORMASI BUKU</p>
    <h2>Detail Buku</h2>
</div>

@if ($detail_buku)

<div class="detail-card">

    <div class="detail-content">

        <span class="category">
            {{ $detail_buku->kategori->nama ?? '-' }}
        </span>

        <h2>{{ $detail_buku->judul }}</h2>

        <div class="detail-info">

            <p>
                <strong>ID Buku</strong>
                <span>{{ $detail_buku->id }}</span>
            </p>

            <p>
                <strong>Penulis</strong>
                <span>{{ $detail_buku->penulis }}</span>
            </p>

            <p>
                <strong>Tahun Terbit</strong>
                <span>{{ $detail_buku->tahun_terbit }}</span>
            </p>

            <p>
                <strong>Kategori</strong>
                <span>{{ $detail_buku->kategori->nama ?? '-' }}</span>
            </p>

        </div>

        @can('mengajukan peminjaman buku')
        <form method="POST" action="{{ route('peminjaman.store') }}"
            style="display:inline-block; margin-right:10px;">
            @csrf
            <input type="hidden" name="buku_id" value="{{ $detail_buku->id }}">
            <button type="submit" class="btn btn-reset">
                Ajukan Peminjaman
            </button>
        </form>
        @endcan

        <a href="{{ route('buku.index') }}" class="btn">
            Kembali ke Daftar Buku
        </a>

    </div>

</div>

@else

<div class="not-found">

    <h2>Buku Tidak Ditemukan</h2>

    <p>
        Data buku yang kamu cari tidak tersedia.
    </p>

    <a href="{{ route('buku.index') }}" class="btn">
        Kembali ke Daftar Buku
    </a>

</div>

@endif

@endsection