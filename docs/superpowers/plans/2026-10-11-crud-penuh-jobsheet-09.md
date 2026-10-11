# CRUD Penuh Jobsheet 09 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Lengkapi CRUD Buku dan Anggota agar sesuai Jobsheet-09, termasuk edit, hapus POST-only, pagination lima data per halaman, pencarian PostgreSQL `ILIKE`, dan dokumentasi perubahan.

**Architecture:** Pertahankan PHP procedural yang sudah digunakan, PDO PostgreSQL, session flash message, dan struktur direktori `buku/` serta `anggota/`. Setiap entitas mendapat halaman edit, processor update, dan endpoint hapus; halaman daftar mengambil data dengan query count + `LIMIT/OFFSET`, sedangkan JavaScript hanya menjadi progressive enhancement untuk konfirmasi submit form hapus.

**Tech Stack:** PHP 8.x, PDO PostgreSQL, PostgreSQL, HTML/CSS, vanilla JavaScript.

**Spec:** `Jobsheet-09-CRUD-Penuh.pdf`, halaman 4, 7-23, dan 25.

## Global Constraints

- Operasi update memakai `UPDATE ... WHERE id = :id` dan mengisi form edit dengan data lama.
- Operasi hapus hanya menerima `POST`; request lain diarahkan kembali ke daftar.
- Pagination menggunakan `perPage = 5`, `LIMIT`, `OFFSET`, dan link yang mempertahankan `q`.
- Pencarian server-side memakai PostgreSQL `ILIKE` dan parameter terikat.
- Tombol hapus berupa `<form class="form-hapus" method="post">` dengan hidden `id`.
- Konfirmasi Cancel harus membatalkan submit; OK harus membiarkan submit server berjalan.
- Validasi server-side update harus sama dengan validasi tambah.
- Jangan menambahkan tabel/modul peminjaman karena tidak diwajibkan Jobsheet-09.
- Buat `PERUBAHAN.md` di root dan `README.md` yang menjelaskan setup serta pengujian CRUD.

## Review Focus

- ID non-integer, ID tidak ditemukan, dan request GET ke endpoint hapus harus aman dan tidak mengubah data; uji setiap endpoint.
- Query pencarian kosong, kata dengan `%`/`_`, dan page di luar batas harus tetap menghasilkan halaman valid; uji list Buku dan Anggota.
- Input ISBN/tahun/stok/field wajib invalid tidak boleh masuk database; uji processor tambah dan edit.
- Cancel pada form hapus tidak boleh mengirim request, sedangkan OK harus menghapus record setelah refresh; uji JavaScript dan endpoint.
- Duplicate `no_anggota` harus menghasilkan flash error yang ramah, bukan exception mentah; uji processor tambah/edit.

---

### Task 1: Shared Database and Form Utilities

**Files:**
- Modify: `includes/koneksi.php`
- Modify: `.gitignore`
- Modify: `includes/header.php`
- Modify: `includes/footer.php`
- Create: `includes/helpers.php`

**Interfaces:**
- Produces `e($value)` for safe HTML output and shared redirect/flash helpers if needed.
- Keeps `$pdo` available from `includes/koneksi.php`.

- [ ] **Step 1: Inspect current files and preserve any already-applied credential fix.**
- [ ] **Step 2: Implement `e($value)` using `htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8')`; use it for page titles, flash messages, and rendered database values.
- [ ] **Step 3: Keep database credentials outside tracked source via environment variables and log detailed PDO errors while showing a generic browser message. Add `.env` to `.gitignore` without committing secrets.
- [ ] **Step 4: Update footer and stale SQL/form comments to identify Jobsheet 09.
- [ ] **Step 5: Run `php -l` on all modified PHP files.

### Task 2: Complete Buku Create/Read

**Files:**
- Modify: `buku/proses_tambah.php`
- Modify: `buku/tambah.php`
- Modify: `buku/list.php`

**Interfaces:**
- Consumes shared `$pdo`, session flash, and `e()`.
- Produces list query behavior `q`, `page`, five rows/page, and links carrying `q`.

- [ ] **Step 1: Move ISBN validation before INSERT and ensure invalid input redirects to `tambah.php` without executing SQL.
- [ ] **Step 2: Render escaped flash output in `tambah.php`.
- [ ] **Step 3: Replace `SELECT *` list loading with count plus `ILIKE` query, `ORDER BY id DESC`, `LIMIT :limit`, and `OFFSET :offset`; bind pagination values as `PDO::PARAM_INT`.
- [ ] **Step 4: Add a GET search form whose `q` value is retained, escaped, and whose pagination links preserve `q` with `urlencode`.
- [ ] **Step 5: Escape every displayed book field and retain a valid empty-state row.
- [ ] **Step 6: Add real Edit link and POST delete form placeholders for the endpoints from Tasks 3-4.
- [ ] **Step 7: Run `php -l buku/list.php buku/tambah.php buku/proses_tambah.php`.

### Task 3: Buku Update

**Files:**
- Create: `buku/edit.php`
- Create: `buku/proses_edit.php`

**Interfaces:**
- `edit.php?id=<integer>` loads one book and renders a prefilled form with hidden `id`.
- `proses_edit.php` accepts POST fields, validates them, updates all book columns, and redirects with flash.

- [ ] **Step 1: Implement integer ID validation and redirect missing/not-found GET requests to `list.php`.
- [ ] **Step 2: Render the same fields as tambah, prefilled with escaped database values and selected category/value preservation.
- [ ] **Step 3: Implement server-side validation before `UPDATE`, including ISBN format, year range, stock nonnegative, and required fields; invalid input redirects to `edit.php?id=...` with flash.
- [ ] **Step 4: Execute prepared `UPDATE buku SET judul=:judul, pengarang=:pengarang, tahun=:tahun, isbn=:isbn, stok=:stok, kategori=:kategori WHERE id=:id` and redirect to list with success flash.
- [ ] **Step 5: Run PHP syntax checks and manually verify edit survives refresh.

### Task 4: Buku Delete and Client Confirmation

**Files:**
- Create: `buku/hapus.php`
- Modify: `assets/js/app.js`
- Modify: `assets/css/style.css`
- Modify: `buku/list.php`

**Interfaces:**
- `hapus.php` accepts only POST `id`, deletes one book with prepared SQL, then redirects with flash.
- JavaScript listens for submit on `.form-hapus`; Cancel calls `preventDefault`, OK leaves native submit intact.

- [ ] **Step 1: Implement POST-only `buku/hapus.php`; reject non-POST and invalid IDs by redirecting to list.
- [ ] **Step 2: Replace inert buttons with an Edit anchor and `<form class="form-hapus" method="post" action="hapus.php">` containing escaped hidden ID and submit button.
- [ ] **Step 3: Replace DOM row removal in `app.js` with delegated form-submit confirmation using the row’s first cell as the label.
- [ ] **Step 4: Add `.btn-edit`, `.form-hapus`, and pagination/search styles required by the PDF.
- [ ] **Step 5: Verify Cancel leaves the row/data intact and OK removes it after reload.

### Task 5: Complete Anggota CRUD

**Files:**
- Modify: `anggota/proses_tambah.php`
- Modify: `anggota/tambah.php`
- Modify: `anggota/list.php`
- Create: `anggota/edit.php`
- Create: `anggota/proses_edit.php`
- Create: `anggota/hapus.php`

**Interfaces:**
- Mirrors the Buku CRUD contracts using `anggota` fields `nama`, `no_anggota`, `alamat`, and `no_hp`.
- List searches member name with `ILIKE`, five rows/page, and persists `q` in pagination.

- [ ] **Step 1: Replace session-only list data with PDO count/list queries and `ILIKE nama` search.
- [ ] **Step 2: Add escaped search, pagination, Edit link, and POST delete form; set table search behavior to member name.
- [ ] **Step 3: Implement prefilled `edit.php`, POST `proses_edit.php`, and prepared `UPDATE anggota ... WHERE id=:id`.
- [ ] **Step 4: Implement POST-only `hapus.php` with prepared `DELETE FROM anggota WHERE id=:id`.
- [ ] **Step 5: Catch duplicate `no_anggota` constraint errors and return a generic flash validation message on both create and update.
- [ ] **Step 6: Run PHP syntax checks and verify add/list/edit/delete/search/pagination manually.

### Task 6: Documentation and Verification

**Files:**
- Create: `README.md`
- Create: `PERUBAHAN.md`
- Modify: `docs/wireframe.md` only if its Jobsheet reference is stale.

**Interfaces:**
- Documentation describes environment/database setup, PHP server command, CRUD test cycle, and all changed/created files.

- [ ] **Step 1: Document PostgreSQL database creation and environment variables without including real passwords.
- [ ] **Step 2: Document test sequence: add, list, search, pagination beyond five records, edit, delete, Cancel delete, and direct GET to hapus redirect.
- [ ] **Step 3: Record every implemented change and explicitly note that borrowing was not added because it is outside Jobsheet-09.
- [ ] **Step 4: Run `php -l` across all PHP files and `node --check assets/js/app.js`.
- [ ] **Step 5: Run a local smoke test with `php -S localhost:8000` when PostgreSQL is available; report unavailable database/runtime checks honestly.
- [ ] **Step 6: Inspect all changed files for accidental credentials, unescaped output, and generated artifacts before completion.
