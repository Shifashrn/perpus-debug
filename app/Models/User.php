<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Data anggota perpustakaan milik pengguna ini.
     */
    public function anggota(): HasOne
    {
        return $this->hasOne(Anggota::class);
    }

    /**
     * Ambil data anggota milik pengguna. Jika belum ada, dibuat otomatis
     * (atau menautkan data anggota lama yang emailnya sama).
     */
    public function anggotaOrCreate(): Anggota
    {
        if ($this->anggota) {
            return $this->anggota;
        }

        $anggota = Anggota::where('email', $this->email)
            ->whereNull('user_id')
            ->first();

        if ($anggota) {
            $anggota->update(['user_id' => $this->id]);
        } else {
            $anggota = Anggota::create([
                'user_id' => $this->id,
                'nama' => $this->name,
                'email' => $this->email,
                'no_telepon' => '-',
            ]);
        }

        $this->setRelation('anggota', $anggota);

        return $anggota;
    }
}