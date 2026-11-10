<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Akun contoh (password semuanya: "password").
     * Akun admin & petugas dibuat lewat seeder = mekanisme yang berwenang,
     * bukan lewat formulir registrasi.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpus.test'],
            ['name' => 'Admin Perpustakaan', 'password' => Hash::make('password')]
        );
        $admin->syncRoles('admin');

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpus.test'],
            ['name' => 'Petugas Perpustakaan', 'password' => Hash::make('password')]
        );
        $petugas->syncRoles('petugas');

        $siswa1 = User::firstOrCreate(
            ['email' => 'siswa1@perpus.test'],
            ['name' => 'Siswa Satu', 'password' => Hash::make('password')]
        );
        $siswa1->syncRoles('siswa');

        $siswa2 = User::firstOrCreate(
            ['email' => 'siswa2@perpus.test'],
            ['name' => 'Siswa Dua', 'password' => Hash::make('password')]
        );
        $siswa2->syncRoles('siswa');

        // Data anggota untuk kedua siswa
        $anggota1 = $siswa1->anggotaOrCreate();
        $anggota1->update(['no_telepon' => '081234567890']);

        $anggota2 = $siswa2->anggotaOrCreate();
        $anggota2->update(['no_telepon' => '081298765432']);

        // Contoh riwayat peminjaman agar halaman riwayat tidak kosong
        $buku = Buku::orderBy('id')->take(3)->get();

        if ($buku->count() >= 3) {
            if ($anggota1->peminjamans()->doesntExist()) {
                Peminjaman::create([
                    'anggota_id' => $anggota1->id,
                    'buku_id' => $buku[0]->id,
                    'tanggal_pinjam' => now()->subDays(14)->toDateString(),
                    'tanggal_kembali' => now()->subDays(7)->toDateString(),
                    'status' => Peminjaman::STATUS_DIKEMBALIKAN,
                ]);

                Peminjaman::create([
                    'anggota_id' => $anggota1->id,
                    'buku_id' => $buku[1]->id,
                    'tanggal_pinjam' => now()->toDateString(),
                    'status' => Peminjaman::STATUS_DIAJUKAN,
                ]);
            }

            if ($anggota2->peminjamans()->doesntExist()) {
                Peminjaman::create([
                    'anggota_id' => $anggota2->id,
                    'buku_id' => $buku[2]->id,
                    'tanggal_pinjam' => now()->subDays(3)->toDateString(),
                    'status' => Peminjaman::STATUS_DIPINJAM,
                ]);
            }
        }
    }
}