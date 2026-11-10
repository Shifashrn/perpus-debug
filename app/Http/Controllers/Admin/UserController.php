<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    private const ROLES = ['admin', 'petugas', 'siswa'];

    public function index(): View
    {
        $users = User::with('roles')->orderBy('name')->get();

        return view('admin.pengguna.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.pengguna.form', [
            'user' => new User(),
            'roles' => self::ROLES,
            'roleSekarang' => 'siswa',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];

        // Menetapkan role hanya boleh bagi yang punya permission "kelola role pengguna"
        $bolehAturRole = $request->user()->can('kelola role pengguna');
        if ($bolehAturRole) {
            $rules['role'] = ['required', Rule::in(self::ROLES)];
        }

        $data = $request->validate($rules, $this->pesan());

        DB::transaction(function () use ($data, $bolehAturRole) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->syncRoles($bolehAturRole ? $data['role'] : 'siswa');
            $user->anggotaOrCreate();
        });

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.pengguna.form', [
            'user' => $user,
            'roles' => self::ROLES,
            'roleSekarang' => $user->getRoleNames()->first(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::unique('anggotas', 'email')->ignore($user->anggota?->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
        ];

        $bolehAturRole = $request->user()->can('kelola role pengguna');
        if ($bolehAturRole) {
            $rules['role'] = ['required', Rule::in(self::ROLES)];
        }

        $data = $request->validate($rules, $this->pesan());

        // Admin tidak boleh menurunkan role akunnya sendiri
        if ($bolehAturRole && $user->is($request->user()) && $data['role'] !== 'admin') {
            return back()
                ->withInput()
                ->withErrors(['role' => 'Kamu tidak dapat mengubah role akunmu sendiri.']);
        }

        DB::transaction(function () use ($data, $user, $bolehAturRole) {
            $user->name = $data['name'];
            $user->email = $data['email'];

            if (! empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            if ($bolehAturRole) {
                $user->syncRoles($data['role']);
            }

            // Samakan data anggota dengan data akun
            $user->anggotaOrCreate()->update([
                'nama' => $user->name,
                'email' => $user->email,
            ]);
        });

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Kamu tidak dapat menghapus akunmu sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }

    private function pesan(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah dipakai.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ];
    }
}