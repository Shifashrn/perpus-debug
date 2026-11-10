@extends('layouts.app')

@section('content')

<div class="not-found">
    <h2>Akses Ditolak</h2>

    <p>
        Kamu tidak memiliki hak akses untuk membuka halaman
        atau menjalankan operasi ini.
    </p>

    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="btn">
        Kembali
    </a>
</div>

@endsection