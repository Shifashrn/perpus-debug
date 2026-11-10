@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">KOLEKSI</p>
    <h2>Daftar Buku</h2>
    <p>Berikut merupakan koleksi buku yang tersedia di PerpusSpace.</p>
</div>

<div style="overflow-x:auto; margin-top:24px;">
    <table style="width:100%;border-collapse:collapse;background:white;">
        <thead>
            <tr style="background:#172554;color:white;">
                <th style="padding:12px;text-align:left;">Kategori</th>
                <th style="padding:12px;text-align:left;">Judul</th>
                <th style="padding:12px;text-align:left;">Penulis</th>
                <th style="padding:12px;text-align:left;">Tahun Terbit</th>
                <th style="padding:12px;text-align:center;">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($bukus as $buku)
                <tr style="border-bottom:1px solid #ddd;">
                    <td style="padding:12px;">{{ $buku->kategori->nama ?? '-' }}</td>

                    {{-- A1 (RENTAN): {!! !!} tidak melakukan escaping --}}
                    <td style="padding:12px;">{!! $buku->judul !!}</td>

                    <td style="padding:12px;">{{ $buku->penulis }}</td>
                    <td style="padding:12px;">{{ $buku->tahun_terbit }}</td>
                    <td style="padding:12px;text-align:center;white-space:nowrap;">
                        <a href="{{ route('buku.show', $buku->id) }}" class="btn-small"
                            style="background-color:#6b7280;color:white;">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding:16px;text-align:center;">
                        Belum ada buku yang tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection