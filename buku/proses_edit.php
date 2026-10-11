<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/helpers.php';
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) redirect('list.php');
$judul = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun = filter_input(INPUT_POST, 'tahun', FILTER_VALIDATE_INT);
$isbn = trim($_POST['isbn'] ?? '');
$stok = filter_input(INPUT_POST, 'stok', FILTER_VALIDATE_INT);
$kategori = trim($_POST['kategori'] ?? '');
$errors = [];
if ($judul === '') $errors[] = 'Judul wajib diisi.';
if ($pengarang === '') $errors[] = 'Pengarang wajib diisi.';
if ($tahun === false || $tahun === null || $tahun < 1900 || $tahun > (int) date('Y')) $errors[] = 'Tahun tidak valid.';
if ($stok === false || $stok === null || $stok < 0) $errors[] = 'Stok tidak boleh negatif.';
if ($isbn !== '' && !preg_match('/^[0-9-]+$/', $isbn)) $errors[] = 'ISBN hanya boleh berisi angka dan tanda hubung.';
if ($errors) { setFlash('error', implode(' ', $errors)); redirect('edit.php?id=' . $id); }
$stmt = $pdo->prepare('UPDATE buku SET judul=:judul, pengarang=:pengarang, tahun=:tahun, isbn=:isbn, stok=:stok, kategori=:kategori WHERE id=:id');
$stmt->execute(['judul'=>$judul, 'pengarang'=>$pengarang, 'tahun'=>$tahun, 'isbn'=>$isbn, 'stok'=>$stok, 'kategori'=>$kategori, 'id'=>$id]);
setFlash('success', 'Buku berhasil diperbarui.');
redirect('list.php');
