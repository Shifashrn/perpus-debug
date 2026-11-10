<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /**
     * Semua data peminjaman (admin & petugas).
     */
    public function index(): View
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->latest('id')
            ->get();

        return view('peminjaman.index', compact('peminjamans'));
    }

    /**
     * A4 (DITAMBAL): Policy memeriksa pemilik sebelum data ditampilkan.
     */
    public function show($id): View
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Admin & petugas: semua. Siswa: hanya miliknya sendiri (PeminjamanPolicy@view)
        Gate::authorize('view', $peminjaman);

        return view('peminjaman.detail', compact('peminjaman'));
    }

    /**
     * Mengajukan peminjaman. Anggota diambil dari pengguna yang login,
     * bukan dari input form, sehingga tidak bisa meminjam atas nama orang lain.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'buku_id' => ['required', 'integer', 'exists:bukus,id'],
        ], [
            'buku_id.required' => 'Buku wajib dipilih.',
            'buku_id.exists' => 'Buku yang dipilih tidak tersedia.',
        ]);

        $anggota = $request->user()->anggotaOrCreate();

        $masihAktif = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $data['buku_id'])
            ->aktif()
            ->exists();

        if ($masihAktif) {
            return back()->with('error', 'Kamu masih punya pengajuan/peminjaman aktif untuk buku ini.');
        }

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $data['buku_id'],
            'tanggal_pinjam' => now()->toDateString(),
            'status' => Peminjaman::STATUS_DIAJUKAN,
        ]);

        return redirect()->route('peminjaman.saya')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan petugas.');
    }

    public function setujui(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan berstatus "diajukan" yang dapat diproses.');
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DIPINJAM,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function tolak(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan berstatus "diajukan" yang dapat diproses.');
        }

        $peminjaman->update(['status' => Peminjaman::STATUS_DITOLAK]);

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }
}