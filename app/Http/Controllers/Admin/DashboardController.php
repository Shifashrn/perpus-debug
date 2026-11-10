<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statistik = [
            'Total Buku' => Buku::count(),
            'Total Pengguna' => User::count(),
            'Total Anggota' => Anggota::count(),
            'Pengajuan Menunggu' => Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'Sedang Dipinjam' => Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count(),
        ];

        return view('admin.dashboard', compact('statistik'));
    }
}