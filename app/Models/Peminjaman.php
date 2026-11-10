<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_DIPINJAM = 'dipinjam';
    public const STATUS_DIKEMBALIKAN = 'dikembalikan';
    public const STATUS_DITOLAK = 'ditolak';

    protected $table = 'peminjamans';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_kembali' => 'date',
        ];
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    /** Peminjaman yang masih berjalan (diajukan / sedang dipinjam). */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_DIAJUKAN, self::STATUS_DIPINJAM]);
    }

    /** Peminjaman yang sudah selesai (dikembalikan / ditolak). */
    public function scopeRiwayat(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_DIKEMBALIKAN, self::STATUS_DITOLAK]);
    }
}