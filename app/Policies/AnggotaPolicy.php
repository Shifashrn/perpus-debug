<?php

namespace App\Policies;

use App\Models\Anggota;
use App\Models\User;

class AnggotaPolicy
{
    /**
     * Riwayat peminjaman seorang anggota hanya boleh dilihat oleh:
     * - admin / petugas (punya permission "kelola peminjaman"), atau
     * - pemilik data itu sendiri (dicek dari identitas pengguna yang login).
     */
    public function lihatRiwayat(User $user, Anggota $anggota): bool
    {
        if ($user->can('kelola peminjaman')) {
            return true;
        }

        return $user->can('melihat riwayat peminjaman sendiri')
            && $anggota->user_id === $user->id;
    }
}