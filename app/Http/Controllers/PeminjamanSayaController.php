<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PeminjamanSayaController extends Controller
{
    /**
     * Status & riwayat peminjaman milik pengguna yang sedang login.
     */
    public function index(Request $request): View
    {
        $anggota = $request->user()->anggotaOrCreate();

        return $this->tampilkan($anggota, true);
    }

    /**
     * Riwayat anggota tertentu. Siswa hanya boleh membuka miliknya sendiri;
     * admin & petugas boleh membuka milik siapa pun (lihat AnggotaPolicy).
     */
    public function riwayat(Request $request, Anggota $anggota): View
    {
        Gate::authorize('lihatRiwayat', $anggota);

        return $this->tampilkan($anggota, $anggota->user_id === $request->user()->id);
    }

    private function tampilkan(Anggota $anggota, bool $milikSendiri): View
    {
        $aktif = Peminjaman::with('buku')
            ->where('anggota_id', $anggota->id)
            ->aktif()
            ->latest('id')
            ->get();

        $riwayat = Peminjaman::with('buku')
            ->where('anggota_id', $anggota->id)
            ->riwayat()
            ->latest('id')
            ->get();

        return view('peminjaman.saya', compact('anggota', 'aktif', 'riwayat', 'milikSendiri'));
    }
}