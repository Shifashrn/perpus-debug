@extends('layouts.app')

@section('content')

<section class="hero">
    <div class="hero-content">
        <p class="subtitle">Hello Readers</p>

        <h2>AYO CARI BUKU MU DISINI !</h2>

        <p>
            PerpusSpace menyediakan berbagai koleksi buku
            yang dapat kamu lihat dengan mudah dalam satu tempat.
        </p>

        <a href="{{ route('buku.index') }}" class="btn">
            Lihat Daftar Buku
        </a>
    </div>
</section>

<section class="info-section">
    <h2>Tentang PerpusSpace</h2>

    <p>
        PerpusSpace merupakan website perpustakaan sederhana
        yang digunakan untuk menampilkan informasi koleksi buku,
        mulai dari judul, penulis, tahun terbit, hingga kategori buku untukmu, readers.
    </p>
</section>

@endsection
