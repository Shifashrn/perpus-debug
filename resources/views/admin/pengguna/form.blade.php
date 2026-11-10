@extends('layouts.app')

@section('content')

@php $mode_ubah = $user->exists; @endphp

<div class="form-card form-wide">
    <p class="subtitle">ADMINISTRATOR</p>
    <h2>{{ $mode_ubah ? 'Ubah Pengguna' : 'Tambah Pengguna' }}</h2>

    <form method="POST"
        action="{{ $mode_ubah ? route('admin.pengguna.update', $user) : route('admin.pengguna.store') }}"
        style="margin-top:20px;">
        @csrf
        @if ($mode_ubah)
            @method('PUT')
        @endif

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
            <label for="password">Kata Sandi {{ $mode_ubah ? '(kosongkan jika tidak diganti)' : '' }}</label>
            <input id="password" class="form-input" type="password" name="password"
                {{ $mode_ubah ? '' : 'required' }} autocomplete="new-password">
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        @can('kelola role pengguna')
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" class="form-input" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('role', $roleSekarang) === $role)>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        @endcan

        <div class="form-actions">
            <button type="submit" class="btn btn-reset">Simpan</button>
            <a href="{{ route('admin.pengguna.index') }}" class="btn-small"
                style="background-color:#6b7280;">Batal</a>
        </div>
    </form>
</div>

@endsection