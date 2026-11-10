@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">PEMINJAMAN</p>
    <h2>{{ $milikSendiri ? 'Peminjaman Saya' : 'Riwayat Peminjaman: '.$anggota->nama }}</h2>
    <p>
        {{ $milikSendiri
            ? 'Status dan riwayat peminjaman milikmu sendiri.'
            : 'Data peminjaman milik anggota ini.' }}
    </p>
</div>

@if ($milikSendiri)
    @can('melihat status peminjaman sendiri')
        <h3 class="section-title">Status Peminjaman Aktif</h3>
        <div class="table-wrap" style="margin-top:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Tgl. Pinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aktif as $p)
                        <tr>
                            <td>{{ $p->buku->judul ?? '-' }}</td>
                            <td>{{ $p->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</td>
                            <td><span class="badge badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;">Tidak ada peminjaman aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endcan
@endif

<h3 class="section-title">Riwayat Peminjaman</h3>
<div class="table-wrap" style="margin-top:0;">
    <table class="data-table">
        <thead>
            <tr>
                <th>Buku</th>
                <th>Tgl. Pinjam</th>
                <th>Tgl. Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayat as $p)
                <tr>
                    <td>{{ $p->buku->judul ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $p->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                    <td><span class="badge badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada riwayat peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection