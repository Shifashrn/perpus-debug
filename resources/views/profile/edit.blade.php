@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">AKUN SAYA</p>
    <h2>Profil Saya</h2>
    <p>Data di halaman ini hanya milik akun yang sedang login.</p>
</div>

<div class="form-card form-wide" style="margin-bottom:24px;">
    <h2>Informasi Profil</h2>

    @can('ubah profil sendiri')
        <form method="POST" action="{{ route('profile.update') }}" style="margin-top:16px;">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="name">Nama</label>
                <input id="name" class="form-input" type="text" name="name"
                    value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" class="form-input" type="email" name="email"
                    value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_telepon">No. Telepon</label>
                <input id="no_telepon" class="form-input" type="text" name="no_telepon"
                    value="{{ old('no_telepon', $anggota->no_telepon) }}" maxlength="20" required>
                @error('no_telepon')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <p class="form-hint" style="margin:0 0 16px;">
                Role: <span class="category">{{ ucfirst($user->getRoleNames()->first() ?? '-') }}</span>
            </p>

            <button type="submit" class="btn btn-reset">Simpan Profil</button>
        </form>
    @else
        <div class="detail-info" style="margin-top:16px;">
            <p><strong>Nama</strong><span>{{ $user->name }}</span></p>
            <p><strong>Email</strong><span>{{ $user->email }}</span></p>
            <p><strong>No. Telepon</strong><span>{{ $anggota->no_telepon }}</span></p>
        </div>
    @endcan
</div>

@can('ubah profil sendiri')
<div class="form-card form-wide">
    <h2>Ubah Kata Sandi</h2>

    <form method="POST" action="{{ route('password.update') }}" style="margin-top:16px;">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="current_password">Kata Sandi Saat Ini</label>
            <input id="current_password" class="form-input" type="password"
                name="current_password" required autocomplete="current-password">
            @error('current_password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi Baru</label>
            <input id="password" class="form-input" type="password" name="password"
                required autocomplete="new-password">
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
            <input id="password_confirmation" class="form-input" type="password"
                name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-reset">Simpan Kata Sandi</button>
    </form>
</div>
@endcan

@endsection