<div class="book-card">

    <div class="book-content">

        <span class="category">
            {{ $buku['kategori'] }}
        </span>

        <h3>{{ $buku['judul'] }}</h3>

        <p>
            <strong>Penulis:</strong>
            {{ $buku['penulis'] }}
        </p>

        <p>
            <strong>Tahun Terbit:</strong>
            {{ $buku['tahun'] }}
        </p>

        {{ $slot }}

        <a href="{{ route('buku.show', $buku['id']) }}" class="btn-small">
            Lihat Detail
        </a>

    </div>

</div>