<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    /**
     * Daftar buku, dirender oleh Blade di server (semua role).
     */
    public function index()
    {
        $bukus = Buku::with('kategori')->orderBy('id')->get();

        return view('buku.daftar', compact('bukus'));
    }

    /**
     * Halaman pengelolaan buku (tambah, ubah, hapus lewat fetch API).
     */
    public function kelola()
    {
        return view('buku.index', ['bisaKelola' => true]);
    }

    public function show($id)
    {
        $detail_buku = Buku::with('kategori')->find($id);

        if (!$detail_buku) {
            abort(404, 'Buku tidak ditemukan.');
        }

        return view('buku.show', compact('detail_buku'));
    }

    /**
     * A2 (DITAMBAL): nilai dikirim terpisah dari struktur query (query binding).
     */
    public function cari(Request $request)
    {
        $judul = $request->query('judul');

        $hasil = DB::select(
            'SELECT id, judul, penulis, tahun_terbit FROM bukus WHERE judul = ?',
            [$judul]
        );

        return response()->json($hasil);
    }

    /**
     * A3 (DITAMBAL): sekarang dipanggil lewat route DELETE yang wajib token CSRF.
     */
    public function destroy(Buku $buku): RedirectResponse
    {
        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}