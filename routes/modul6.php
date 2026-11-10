<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Modul 6 - SOAL 3 (Gate kelola-buku)
| Semua route di file ini berada di dalam group middleware "auth".
|--------------------------------------------------------------------------
*/

// Gate "kelola-buku": hanya admin & petugas (menggantikan permission:kelola data buku)

// Tambah buku
Route::get('/buku/create', [BukuController::class, 'create'])
    ->middleware('can:kelola-buku')
    ->name('buku.create');

// Halaman pengelolaan buku (tambah, ubah, hapus)
Route::get('/kelola/buku', [BukuController::class, 'kelola'])
    ->middleware('can:kelola-buku')
    ->name('buku.kelola');

// Hapus buku
Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])
    ->middleware('can:kelola-buku')
    ->name('buku.destroy');

// Pencarian buku (query memakai binding)
Route::get('/buku-cari', [BukuController::class, 'cari'])
    ->name('buku.cari');

// Detail peminjaman (pemeriksaan pemilik ditambahkan pada Soal 4)
Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
    ->whereNumber('id')
    ->name('peminjaman.show');