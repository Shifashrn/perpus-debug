<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Status peminjaman: diajukan -> dipinjam -> dikembalikan (atau ditolak).
     */
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->string('status', 20)->default('diajukan')->after('tanggal_kembali');
        });

        // Data lama dari Modul 4: sesuaikan status dengan tanggal_kembali
        DB::table('peminjamans')->whereNull('tanggal_kembali')->update(['status' => 'dipinjam']);
        DB::table('peminjamans')->whereNotNull('tanggal_kembali')->update(['status' => 'dikembalikan']);
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};