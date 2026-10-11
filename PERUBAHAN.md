# Perubahan Jobsheet 09

## CRUD Buku

- Menambahkan halaman `buku/edit.php`, processor `buku/proses_edit.php`, dan endpoint `buku/hapus.php`.
- Mengubah daftar Buku agar mengambil lima data per halaman, mendukung pencarian server-side dengan PostgreSQL `ILIKE`, link edit, dan form hapus POST.
- Memperbaiki validasi tambah/edit Buku, termasuk ISBN, tahun, dan stok.
- Menampilkan flash message validasi pada form tambah/edit.

## CRUD Anggota

- Mengubah `anggota/list.php` dari session-only menjadi query PostgreSQL.
- Menambahkan pagination, pencarian nama dengan `ILIKE`, link edit, dan form hapus POST.
- Menambahkan `anggota/edit.php`, `anggota/proses_edit.php`, dan `anggota/hapus.php`.
- Menangani nomor anggota duplikat dengan flash message, bukan exception mentah.

## Antarmuka dan keamanan

- Konfirmasi hapus JavaScript sekarang berjalan pada event submit dan tidak menghapus baris secara kosmetik.
- Menambahkan gaya link edit, form hapus, pencarian, dan pagination.
- Menambahkan helper `e()` untuk escaping output HTML.
- Menggunakan file `.env` sebagai satu-satunya sumber credential database; file `.env` diabaikan Git dan detail error hanya dicatat ke log server.
- Memperbarui label Jobsheet menjadi Jobsheet 09.

## Latihan 7.4

1. **Konfirmasi sebelum update** — menambahkan `initUpdateConfirm()` di `assets/js/app.js`: form edit (`action` menuju `proses_edit.php`) menampilkan dialog konfirmasi "Yakin ingin menyimpan perubahan?" sebelum submit; memilih Cancel membatalkan submit dengan `preventDefault()`.
2. **`perPage = 10`** — mengubah `$perPage` dari `5` menjadi `10` di `buku/list.php` dan `anggota/list.php`, sehingga setiap halaman daftar menampung sepuluh data.
3. **Pencarian juga mencocokkan pengarang** — query pencarian di `buku/list.php` memakai `WHERE judul ILIKE :search_title OR pengarang ILIKE :search_author`, sehingga kata kunci cocok dengan judul maupun pengarang buku.

## Verifikasi

- PHP syntax check dijalankan pada seluruh file PHP.
- JavaScript syntax check dijalankan dengan `node --check assets/js/app.js`.
- Smoke test database perlu dijalankan pada mesin yang memiliki PostgreSQL aktif dan environment variable lengkap.

Modul peminjaman tidak ditambahkan karena Jobsheet 09 hanya mensyaratkan CRUD Buku dan Anggota.
