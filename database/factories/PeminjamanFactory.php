<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        $tanggalKembali = fake()->optional()->dateTimeBetween('now', '+1 month')?->format('Y-m-d');

        return [
            'anggota_id' => Anggota::inRandomOrder()->first()->id,
            'buku_id' => Buku::inRandomOrder()->first()->id,
            'tanggal_pinjam' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'tanggal_kembali' => $tanggalKembali,
            'status' => $tanggalKembali ? 'dikembalikan' : 'dipinjam',
        ];
    }
}