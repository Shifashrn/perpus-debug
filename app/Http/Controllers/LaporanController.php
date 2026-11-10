<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(): View
    {
        $ringkasan = [
            'Total Buku' => Buku::count(),
            'Total Anggota' => Anggota::count(),
            'Total Peminjaman' => Peminjaman::count(),
        ];

        $perStatus = Peminjaman::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $perKategori = Kategori::withCount('bukus')->orderBy('nama')->get();

        $bukuPopuler = Buku::with('kategori')
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->take(5)
            ->get();

        return view('laporan.index', compact('ringkasan', 'perStatus', 'perKategori', 'bukuPopuler'));
    }
}