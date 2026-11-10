<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Modul 6 - SOAL 1 (kondisi RENTAN, untuk audit keamanan)
| Semua route di file ini berada di dalam group middleware "auth".
|--------------------------------------------------------------------------
*/

// Halaman pengelolaan buku (admin & petugas)
Route::get('/kelola/buku', [BukuController::class, 'kelola'])
    ->middleware('permission:kelola data buku')
    ->name('buku.kelola');

// A2 (RENTAN): SQL Injection - input digabung langsung ke string SQL
Route::get('/buku-cari', [BukuController::class, 'cari'])
    ->name('buku.cari');

// A3 (RENTAN): penghapusan lewat GET, bisa dipicu tautan dari situs lain (CSRF)
Route::get('/buku/{buku}/hapus', [BukuController::class, 'destroy'])
    ->name('buku.hapus');

// A4 (RENTAN): IDOR - detail peminjaman tanpa pemeriksaan pemilik
Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
    ->whereNumber('id')
    ->name('peminjaman.show');