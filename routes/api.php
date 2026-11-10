<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Models\Buku;
use App\Models\Kategori;

/*
| API ini dipanggil lewat fetch() dari halaman web, jadi memakai session login.
| - "web"  : mengaktifkan session + CSRF agar pengguna yang login dikenali
| - "auth" : wajib login (tanpa login -> 401 JSON)
| - "permission:..." / "can:..." : dibatasi hak akses (tanpa hak -> 403 JSON)
*/
Route::middleware(['web', 'auth'])->group(function () {

    // Lihat daftar & detail buku (admin, petugas, siswa)
    Route::apiResource('buku', BukuController::class)
        ->only(['index', 'show'])
        ->middleware('permission:lihat daftar buku')
        ->names('api.buku');

    // Tambah, ubah, hapus buku (admin & petugas) -> memakai Gate kelola-buku
    Route::apiResource('buku', BukuController::class)
        ->only(['store', 'update', 'destroy'])
        ->middleware('can:kelola-buku')
        ->names('api.buku');

    Route::get('/rekap/buku-per-kategori', function () {
        $data = Kategori::withCount('bukus')->get();

        return response()->json([
            'data' => $data,
        ], 200);
    })->middleware('permission:lihat daftar buku');

    Route::get('/rekap/peminjaman-per-buku', function () {
        $data = Buku::withCount('peminjamans')
            ->get(['id', 'judul']);

        return response()->json([
            'data' => $data,
        ], 200);
    })->middleware('permission:lihat laporan perpustakaan');
});