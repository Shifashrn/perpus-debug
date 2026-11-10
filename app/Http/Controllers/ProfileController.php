<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Profil SELALU diambil dari pengguna yang sedang login ($request->user()),
     * tidak ada parameter id, sehingga tidak mungkin membuka profil orang lain.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'anggota' => $user->anggotaOrCreate(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->safe()->only(['name', 'email']))->save();

        // Samakan data anggota perpustakaan dengan data akun
        $user->anggotaOrCreate()->update([
            'nama' => $user->name,
            'email' => $user->email,
            'no_telepon' => $request->validated('no_telepon'),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}