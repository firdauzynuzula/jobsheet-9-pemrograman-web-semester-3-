# SIMPUS-Mini — Jobsheet 09

Aplikasi PHP + PostgreSQL untuk CRUD Buku dan Anggota.

## Menjalankan

1. Buat database PostgreSQL bernama `simpus_mini`.
2. Jalankan `sql/15_buku_anggota.sql` pada database tersebut.
3. Isi credential PostgreSQL pada file `.env` di root proyek sesuai konfigurasi lokal.
4. Jalankan dari root proyek:

```text
php -S localhost:8000
```

5. Buka `http://localhost:8000`.

## Fitur Jobsheet 09

- Tambah, lihat, edit, dan hapus Buku.
- Tambah, lihat, edit, dan hapus Anggota.
- Hapus memakai POST dan konfirmasi browser.
- Pencarian server-side PostgreSQL dengan `ILIKE`.
- Pagination sepuluh data per halaman dengan `LIMIT/OFFSET` (sesuai Latihan 7.4).

## Verifikasi manual

Uji alur tambah -> daftar -> cari -> edit -> daftar -> hapus. Tambahkan lebih dari lima data untuk memeriksa pagination. Pada dialog hapus, Cancel tidak boleh mengirim request; GET langsung ke `buku/hapus.php` atau `anggota/hapus.php` harus kembali ke daftar.
