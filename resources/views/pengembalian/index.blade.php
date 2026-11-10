@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">OPERASIONAL</p>
    <h2>Data Pengembalian</h2>
    <p>Catat pengembalian buku yang sedang dipinjam.</p>
</div>

<h3 class="section-title">Sedang Dipinjam</h3>
<div class="table-wrap" style="margin-top:0;">
    <table class="data-table">
        <thead>
            <tr>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tgl. Pinjam</th>
                <th class="center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dipinjam as $p)
                <tr>
                    <td>{{ $p->anggota->nama ?? '-' }}</td>
                    <td>{{ $p->buku->judul ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</td>
                    <td class="center">
                        @can('mencatat pengembalian buku')
                            <form method="POST" action="{{ route('pengembalian.store', $p) }}" class="inline-form">
                                @csrf
                                <button type="submit" class="btn-small btn-reset">Catat Pengembalian</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Tidak ada buku yang sedang dipinjam.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3 class="section-title">Sudah Dikembalikan</h3>
<div class="table-wrap" style="margin-top:0;">
    <table class="data-table">
        <thead>
            <tr>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tgl. Pinjam</th>
                <th>Tgl. Kembali</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dikembalikan as $p)
                <tr>
                    <td>{{ $p->anggota->nama ?? '-' }}</td>
                    <td>{{ $p->buku->judul ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $p->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada pengembalian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection