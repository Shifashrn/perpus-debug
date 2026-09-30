<?php

namespace App\Http\Controllers;

class BukuController extends Controller
{
    public function index()
    {
        $buku = $this->getDataBuku();

        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $buku = $this->getDataBuku();
        $detail_buku = null;

        foreach ($buku as $item) {
            if ($item['id'] == $id) {
                $detail_buku = $item;
                break;
            }
        }

        return view('buku.show', compact('detail_buku'));
    }

    private function getDataBuku()
    {
        return [
            [
                'id' => 1,
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'tahun' => 2005,
                'kategori' => 'Novel'
            ],
            [
                'id' => 2,
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'tahun' => 1980,
                'kategori' => 'Sejarah'
            ],
            [
                'id' => 3,
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'tahun' => 2018,
                'kategori' => 'Pengembangan Diri'
            ],
            [
                'id' => 4,
                'judul' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'tahun' => 2018,
                'kategori' => 'Pengembangan Diri'
            ],
            [
                'id' => 5,
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'Ahmad Fuadi',
                'tahun' => 2009,
                'kategori' => 'Novel'
            ],
            [
                'id' => 6,
                'judul' => 'Mariposa',
                'penulis' => 'Luluk HF',
                'tahun' => 2018,
                'kategori' => 'Novel'
            ]
        ];
    }
}
