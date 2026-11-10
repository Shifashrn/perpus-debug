<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PengembalianController extends Controller
{
    public function index(): View
    {
        $dipinjam = Peminjaman::with(['anggota', 'buku'])
            ->where('status', Peminjaman::STATUS_DIPINJAM)
            ->latest('id')
            ->get();

        $dikembalikan = Peminjaman::with(['anggota', 'buku'])
            ->where('status', Peminjaman::STATUS_DIKEMBALIKAN)
            ->latest('id')
            ->get();

        return view('pengembalian.index', compact('dipinjam', 'dikembalikan'));
    }

    /**
     * Mencatat pengembalian buku.
     */
    public function store(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DIPINJAM) {
            return back()->with('error', 'Hanya buku yang sedang dipinjam yang dapat dicatat pengembaliannya.');
        }

        $peminjaman->update([
            'status' => Peminjaman::STATUS_DIKEMBALIKAN,
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('success', 'Pengembalian buku berhasil dicatat.');
    }
}