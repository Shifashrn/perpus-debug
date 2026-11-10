<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statistik = [
            'Total Buku' => Buku::count(),
            'Pengajuan Menunggu' => Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'Sedang Dipinjam' => Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count(),
            'Sudah Dikembalikan' => Peminjaman::where('status', Peminjaman::STATUS_DIKEMBALIKAN)->count(),
        ];

        return view('petugas.dashboard', compact('statistik'));
    }
}