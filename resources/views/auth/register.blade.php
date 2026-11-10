@extends('layouts.app')

@section('content')

<div class="form-card">
    <p class="subtitle">AKUN</p>
    <h2>Daftar</h2>
    <p>Buat akun anggota untuk meminjam buku di PerpusSpace.</p>

    {{-- Tidak ada pilihan role: akun baru otomatis menjadi "siswa" --}}
    <form method="POST" action="{{ route('register') }}" style="margin-top:20px;">
        @csrf

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input id="name" class="form-input" type="text" name="name"
                value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" class="form-input" type="email" name="email"
                value="{{ old('email') }}" required autocomplete="username">
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="no_telepon">No. Telepon</label>
            <input id="no_telepon" class="form-input" type="text" name="no_telepon"
                value="{{ old('no_telepon') }}" maxlength="20" required>
            @error('no_telepon')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input id="password" class="form-input" type="password" name="password"
                required autocomplete="new-password">
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" class="form-input" type="password"
                name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-reset">Daftar</button>

        <p class="form-hint">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </p>
    </form>
</div>

@endsection