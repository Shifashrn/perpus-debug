@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">KOLEKSI</p>

    <h2>Daftar Buku</h2>

    <p>
        Berikut merupakan koleksi buku yang tersedia di PerpusSpace.
    </p>
</div>

<div class="book-grid">

    @foreach ($buku as $item)

    <x-buku-card :buku="$item">

        <p class="slot-info">
            Koleksi PerpusSpace
        </p>

    </x-buku-card>

    @endforeach

</div>

@endsection