@extends('layouts.app')

@section('content')

<div class="form-card">
    <p class="subtitle">AKUN</p>
    <h2>Masuk</h2>
    <p>Silakan masuk untuk mengakses PerpusSpace.</p>

    <form method="POST" action="{{ route('login') }}" style="margin-top:20px;">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" class="form-input" type="email" name="email"
                value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input id="password" class="form-input" type="password" name="password"
                required autocomplete="current-password">
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label style="font-weight:normal;">
                <input type="checkbox" name="remember"> Ingat saya
            </label>
        </div>

        <button type="submit" class="btn btn-reset">Masuk</button>

        <p class="form-hint">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </p>
    </form>
</div>

@endsection