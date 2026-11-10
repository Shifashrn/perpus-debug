@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">OPERASIONAL</p>
    <h2>Data Peminjaman</h2>
    <p>Setujui atau tolak pengajuan peminjaman dari anggota.</p>
</div>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tgl. Pinjam</th>
                <th>Tgl. Kembali</th>
                <th>Status</th>
                <th class="center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamans as $p)
                <tr>
                    <td>
                        <a href="{{ route('riwayat.anggota', $p->anggota_id) }}">
                            {{ $p->anggota->nama ?? '-' }}
                        </a>
                    </td>
                    <td>{{ $p->buku->judul ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $p->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                    <td><span class="badge badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    <td class="center" style="white-space:nowrap;">
                        @if ($p->status === 'diajukan')
                            @can('memproses persetujuan peminjaman')
                                <form method="POST" action="{{ route('peminjaman.setujui', $p) }}" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-small btn-reset">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('peminjaman.tolak', $p) }}" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-small btn-reset"
                                        style="background-color:#dc2626;">Tolak</button>
                                </form>
                            @endcan
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada data peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection