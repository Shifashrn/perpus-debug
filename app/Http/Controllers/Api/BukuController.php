<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans');

        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        $buku = $query->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return BukuResource::collection($buku);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required|integer|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
        ], [
            'kategori_id.required' => 'Kategori buku wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak tersedia.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.min' => 'Judul buku minimal 3 karakter.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penulis.min' => 'Nama penulis minimal 3 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.min' => 'Tahun terbit tidak valid.',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang.',
        ]);

        $buku = Buku::create($data);
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => new BukuResource($buku),
        ], 201);
    }

    public function show(string $id)
    {
        $buku = Buku::with(['kategori', 'peminjamans'])
            ->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $data = $request->validate([
            'kategori_id' => 'required|integer|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
        ], [
            'kategori_id.required' => 'Kategori buku wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak tersedia.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.min' => 'Judul buku minimal 3 karakter.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penulis.min' => 'Nama penulis minimal 3 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.min' => 'Tahun terbit tidak valid.',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang.',
        ]);

        $buku->update($data);
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function destroy(string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus.',
        ], 200);
    }
}
