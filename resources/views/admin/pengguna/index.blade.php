@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">ADMINISTRATOR</p>
    <h2>Kelola Pengguna & Role</h2>
    <p>Hanya admin yang dapat mengelola akun dan menetapkan role pengguna.</p>
</div>

<a href="{{ route('admin.pengguna.create') }}" class="btn">+ Tambah Pengguna</a>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th class="center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td><span class="category">{{ ucfirst($u->roles->first()->name ?? 'tanpa role') }}</span></td>
                    <td class="center" style="white-space:nowrap;">
                        <a href="{{ route('admin.pengguna.edit', $u) }}" class="btn-small">Ubah</a>

                        @if (! $u->is(auth()->user()))
                            <form method="POST" action="{{ route('admin.pengguna.destroy', $u) }}"
                                class="inline-form"
                                onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small btn-reset"
                                    style="background-color:#dc2626;">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection