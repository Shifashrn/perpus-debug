<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ $title ?? 'PerpusSpace' }}</title>

	@vite(['resources/css/app.css'])
</head>

<body>

	<header class="header">
		<div class="container">
			<h1>PerpusSpace</h1>
			<p>Ruang untuk menemukan dan membaca berbagai koleksi
				buku.</p>
		</div>
	</header>

	<nav class="navbar">
		<div class="container nav-content @auth nav-auth @endauth">
			<a href="{{ route('home') }}">Beranda</a>
			<a href="{{ route('buku.index') }}">Daftar Buku</a>

			@auth
			@role('admin')
			<a href="{{ route('admin.dashboard') }}">Dashboard</a>
			@endrole

			@role('petugas')
			<a href="{{ route('petugas.dashboard') }}">Dashboard</a>
			@endrole

			@can('kelola data buku')
			<a href="{{ route('buku.kelola') }}">Kelola Buku</a>
			@endcan

			@can('kelola data pengguna')
			<a href="{{ route('admin.pengguna.index') }}">Pengguna</a>
			@endcan

			@can('kelola peminjaman')
			<a href="{{ route('peminjaman.index') }}">Peminjaman</a>
			@endcan

			@can('kelola pengembalian')
			<a href="{{ route('pengembalian.index') }}">Pengembalian</a>
			@endcan

			@can('lihat laporan perpustakaan')
			<a href="{{ route('laporan.index') }}">Laporan</a>
			@endcan

			@can('melihat status peminjaman sendiri')
			<a href="{{ route('peminjaman.saya') }}">Pinjaman Saya</a>
			@endcan

			@can('lihat profil sendiri')
			<a href="{{ route('profile.edit') }}" class="nav-push">Profil</a>
			@endcan

			<form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
				@csrf
				<button type="submit" class="nav-logout">Keluar</button>
			</form>
			@else
			<a href="{{ route('login') }}">Masuk</a>
			<a href="{{ route('register') }}">Daftar</a>
			@endauth
		</div>
	</nav>

	<main class="container content">
		@if (session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
		@endif

		@if (session('error'))
		<div class="alert alert-error">{{ session('error') }}</div>
		@endif

		@yield('content')
	</main>

	<footer class="footer">
		<p>PerpusSpace &copy; 2026</p>
	</footer>

</body>

</html>