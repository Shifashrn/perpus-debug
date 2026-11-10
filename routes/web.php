<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamanSayaController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Halaman yang dilindungi (wajib login -> middleware "auth")
| Setiap route tambahan dibatasi lagi dengan middleware "permission" / "can"
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Pengarah dashboard sesuai role (admin / petugas / siswa)
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('permission:akses dashboard admin')
        ->name('admin.dashboard');

    Route::get('/petugas/dashboard', [PetugasDashboardController::class, 'index'])
        ->middleware('permission:akses dashboard petugas')
        ->name('petugas.dashboard');

    // Buku: daftar & detail (semua role)
    Route::middleware('permission:lihat daftar buku')->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])
            ->name('buku.index');

        Route::get('/buku/{id}', [BukuController::class, 'show'])
            ->whereNumber('id')
            ->name('buku.show');
    });

    // Route Modul 6 (keamanan web dan otorisasi) ada di file terpisah.
    // File ini di-require DI DALAM group "auth", jadi semua route-nya
    // otomatis berada di balik middleware auth.
    require __DIR__.'/modul6.php';

    // Pengelolaan akun & role pengguna (khusus admin)
    Route::prefix('admin/pengguna')
        ->name('admin.pengguna.')
        ->middleware('permission:kelola data pengguna')
        ->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/tambah', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/ubah', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

    // Peminjaman: pengajuan (semua role)
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.store');

    // Peminjaman: kelola & proses persetujuan (admin & petugas)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola peminjaman')
        ->name('peminjaman.index');

    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.setujui');

    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.tolak');

    // Pengembalian (admin & petugas)
    Route::get('/pengembalian', [PengembalianController::class, 'index'])
        ->middleware('permission:kelola pengembalian')
        ->name('pengembalian.index');

    Route::post('/pengembalian/{peminjaman}', [PengembalianController::class, 'store'])
        ->middleware('permission:mencatat pengembalian buku')
        ->name('pengembalian.store');

    // Laporan perpustakaan (admin & petugas)
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');

    // Status & riwayat peminjaman milik sendiri (semua role)
    Route::get('/peminjaman-saya', [PeminjamanSayaController::class, 'index'])
        ->middleware('permission:melihat status peminjaman sendiri|melihat riwayat peminjaman sendiri')
        ->name('peminjaman.saya');

    // Riwayat anggota tertentu: dicek lagi lewat Policy (identitas pengguna)
    Route::get('/riwayat-peminjaman/{anggota}', [PeminjamanSayaController::class, 'riwayat'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')
        ->name('riwayat.anggota');

    // Profil milik sendiri (semua role)
    Route::get('/profil', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profil', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');

    Route::put('/profil/password', [PasswordController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('password.update');
});

// Route registrasi, login, dan logout (Laravel Breeze)
require __DIR__.'/auth.php';