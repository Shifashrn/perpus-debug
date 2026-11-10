@extends('layouts.app')

@section('content')

<div class="page-header">
    <p class="subtitle">KOLEKSI</p>
    <h2>Daftar Buku</h2>
    <p>Berikut merupakan koleksi buku yang tersedia di PerpusSpace.</p>
</div>

<div id="pesan" data-kelola="{{ $bisaKelola ? '1' : '0' }}" style="display:none; margin-bottom:16px;"></div>

@if ($bisaKelola)
{{-- FORM TAMBAH DAN EDIT BUKU --}}
<div class="book-card" style="margin-bottom:24px;">
    <div class="book-content">
        <h3 id="judul-form">Tambah Buku</h3>

        <form id="form-buku">
            <label for="kategori_id">Kategori</label>
            <select id="kategori_id" required
                style="display:block;width:100%;padding:10px;margin:8px 0 16px;">
                <option value="">Pilih kategori</option>
            </select>

            <label for="judul">Judul Buku</label>
            <input id="judul" type="text" minlength="3" maxlength="150"
                required style="display:block;width:100%;padding:10px;margin:8px 0 16px;">

            <label for="penulis">Penulis</label>
            <input id="penulis" type="text" minlength="3" maxlength="100"
                required style="display:block;width:100%;padding:10px;margin:8px 0 16px;">

            <label for="tahun_terbit">Tahun Terbit</label>
            <input id="tahun_terbit" type="number" min="1900"
                max="{{ date('Y') }}" required
                style="display:block;width:100%;padding:10px;margin:8px 0 16px;">

            <button type="submit" id="tombol-simpan" class="btn-small">
                Simpan Buku
            </button>

            <button type="button" id="tombol-batal" class="btn-small"
                style="display:none;">
                Batal Edit
            </button>
        </form>
    </div>
</div>
@endif

{{-- TABEL DAFTAR BUKU --}}
<div style="overflow-x:auto; margin-top:24px;">
    <table style="width:100%;border-collapse:collapse;background:white;">
        <thead>
            <tr style="background:#172554;color:white;">
                <th style="padding:12px;text-align:left;">Kategori</th>
                <th style="padding:12px;text-align:left;">Judul</th>
                <th style="padding:12px;text-align:left;">Penulis</th>
                <th style="padding:12px;text-align:left;">Tahun Terbit</th>
                <th style="padding:12px;text-align:center;">Aksi</th>
            </tr>
        </thead>

        <tbody id="isi-tabel-buku">
            <tr>
                <td colspan="5" style="padding:16px;text-align:center;">
                    Memuat daftar buku...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    // true = halaman "Kelola Buku" (boleh tambah/ubah/hapus), false = hanya melihat daftar
    const bisaKelola = document.getElementById('pesan').dataset.kelola === '1';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const isiTabelBuku = document.getElementById('isi-tabel-buku');
    const formBuku = document.getElementById('form-buku');
    const pilihanKategori = document.getElementById('kategori_id');
    const pesan = document.getElementById('pesan');
    const judulForm = document.getElementById('judul-form');
    const tombolSimpan = document.getElementById('tombol-simpan');
    const tombolBatal = document.getElementById('tombol-batal');

    let idBukuDiedit = null;

    // Menampilkan pesan berhasil atau gagal
    function tampilkanPesan(teks, berhasil = false) {
        pesan.textContent = teks;
        pesan.style.display = 'block';
        pesan.style.color = berhasil ? 'green' : '#b91c1c';
    }

    // Mengambil daftar kategori dari API
    async function muatKategori() {
        try {
            const response = await fetch('/api/rekap/buku-per-kategori', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil daftar kategori.');
            }

            const hasil = await response.json();

            pilihanKategori.innerHTML =
                '<option value="">Pilih kategori</option>';

            hasil.data.forEach(function(kategori) {
                const opsi = document.createElement('option');
                opsi.value = kategori.id;
                opsi.textContent = kategori.nama;
                pilihanKategori.appendChild(opsi);
            });
        } catch (error) {
            tampilkanPesan(error.message);
        }
    }

    // Mengambil dan menampilkan data buku dalam tabel
    async function tampilkanBuku() {
        try {
            const response = await fetch('/api/buku', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data buku.');
            }

            const hasil = await response.json();
            isiTabelBuku.innerHTML = '';

            if (!hasil.data || hasil.data.length === 0) {
                isiTabelBuku.innerHTML = `
                    <tr>
                        <td colspan="5"
                            style="padding:16px;text-align:center;">
                            Belum ada buku yang tersedia.
                        </td>
                    </tr>
                `;
                return;
            }

            hasil.data.forEach(function(buku) {
                const baris = document.createElement('tr');
                baris.style.borderBottom = '1px solid #ddd';

                // Kolom kategori, judul, penulis, dan tahun terbit
                const data = [
                    buku.kategori ?? '-',
                    buku.judul ?? '-',
                    buku.penulis ?? '-',
                    buku.tahun_terbit ?? '-'
                ];

                data.forEach(function(nilai) {
                    const kolom = document.createElement('td');
                    kolom.style.padding = '12px';
                    kolom.textContent = nilai;
                    baris.appendChild(kolom);
                });

                // Kolom aksi
                const kolomAksi = document.createElement('td');
                kolomAksi.style.padding = '12px';
                kolomAksi.style.textAlign = 'center';
                kolomAksi.style.whiteSpace = 'nowrap';

                // Tombol Detail
                const tombolDetail = document.createElement('a');
                tombolDetail.className = 'btn-small';
                tombolDetail.textContent = 'Detail';
                tombolDetail.href = '/buku/' + encodeURIComponent(buku.id);
                tombolDetail.style.backgroundColor = '#6b7280';
                tombolDetail.style.color = 'white';
                tombolDetail.style.marginRight = '8px';

                // Tombol Ubah
                const tombolEdit = document.createElement('button');
                tombolEdit.type = 'button';
                tombolEdit.className = 'btn-small';
                tombolEdit.textContent = 'Ubah';
                tombolEdit.style.marginRight = '8px';

                tombolEdit.addEventListener('click', function() {
                    mulaiEdit(buku);
                });

                // Tombol Hapus
                const tombolHapus = document.createElement('button');
                tombolHapus.type = 'button';
                tombolHapus.className = 'btn-small';
                tombolHapus.textContent = 'Hapus';
                tombolHapus.style.backgroundColor = '#dc2626';
                tombolHapus.style.color = 'white';

                tombolHapus.addEventListener('click', function() {
                    hapusBuku(buku.id, buku.judul);
                });

                kolomAksi.append(tombolDetail);

                // Tombol Ubah & Hapus hanya untuk yang berhak mengelola buku
                if (bisaKelola) {
                    kolomAksi.append(tombolEdit, tombolHapus);
                }

                baris.appendChild(kolomAksi);
                isiTabelBuku.appendChild(baris);
            });
        } catch (error) {
            isiTabelBuku.innerHTML = '';

            const baris = document.createElement('tr');
            const kolom = document.createElement('td');

            kolom.colSpan = 5;
            kolom.style.padding = '16px';
            kolom.textContent = error.message;

            baris.appendChild(kolom);
            isiTabelBuku.appendChild(baris);
        }
    }

    // Mengisi formulir dengan data buku yang dipilih
    function mulaiEdit(buku) {
        idBukuDiedit = buku.id;

        document.getElementById('judul').value = buku.judul ?? '';
        document.getElementById('penulis').value = buku.penulis ?? '';
        document.getElementById('tahun_terbit').value =
            buku.tahun_terbit ?? '';

        pilihanKategori.value = String(buku.kategori_id ?? '');

        judulForm.textContent = 'Ubah Buku';
        tombolSimpan.textContent = 'Simpan Perubahan';
        tombolBatal.style.display = 'inline-block';
        pesan.style.display = 'none';

        formBuku.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    // Mengembalikan formulir ke mode tambah
    function batalEdit() {
        idBukuDiedit = null;
        formBuku.reset();
        judulForm.textContent = 'Tambah Buku';
        tombolSimpan.textContent = 'Simpan Buku';
        tombolBatal.style.display = 'none';
    }

    // Menghapus buku setelah konfirmasi
    async function hapusBuku(id, judulBuku) {
        if (!confirm('Yakin ingin menghapus buku "' + judulBuku + '"?')) {
            return;
        }

        try {
            const response = await fetch(
                '/api/buku/' + encodeURIComponent(id), {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                }
            );

            const hasil = await response.json();

            if (!response.ok) {
                throw new Error(hasil.message || 'Buku gagal dihapus.');
            }

            if (String(idBukuDiedit) === String(id)) {
                batalEdit();
            }

            tampilkanPesan(
                hasil.message || 'Buku berhasil dihapus.',
                true
            );

            await tampilkanBuku();
        } catch (error) {
            tampilkanPesan(
                error.message ||
                'Terjadi kesalahan saat menghapus buku.'
            );
        }
    }

    if (bisaKelola) {

    // Tombol batal edit
    tombolBatal.addEventListener('click', function() {
        batalEdit();
        pesan.style.display = 'none';
    });

    // Mengirim data untuk tambah atau ubah buku
    formBuku.addEventListener('submit', async function(event) {
        event.preventDefault();
        pesan.style.display = 'none';

        const dataBuku = {
            kategori_id: Number(pilihanKategori.value),
            judul: document.getElementById('judul').value.trim(),
            penulis: document.getElementById('penulis').value.trim(),
            tahun_terbit: Number(
                document.getElementById('tahun_terbit').value
            )
        };

        const sedangEdit = idBukuDiedit !== null;
        const url = sedangEdit ?
            '/api/buku/' + encodeURIComponent(idBukuDiedit) :
            '/api/buku';

        try {
            const response = await fetch(url, {
                method: sedangEdit ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(dataBuku)
            });

            const hasil = await response.json();

            if (!response.ok) {
                const detailError = hasil.message ||
                    Object.values(hasil.errors || {}).flat().join(' ');

                throw new Error(
                    detailError || 'Data buku gagal disimpan.'
                );
            }

            tampilkanPesan(
                hasil.message || (
                    sedangEdit ?
                    'Buku berhasil diperbarui.' :
                    'Buku berhasil ditambahkan.'
                ),
                true
            );

            batalEdit();
            await tampilkanBuku();
        } catch (error) {
            tampilkanPesan(
                error.message ||
                'Terjadi kesalahan saat menyimpan buku.'
            );
        }
    });

    } // akhir if (bisaKelola)

    // Memuat kategori (hanya halaman kelola) lalu data buku
    if (bisaKelola) {
        muatKategori().then(function() {
            tampilkanBuku();
        });
    } else {
        tampilkanBuku();
    }
</script>

@endsection