<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/helpers.php';
$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');
if ($nama === '' || $noAnggota === '') { setFlash('error', 'Nama dan No. Anggota wajib diisi.'); redirect('tambah.php'); }
try {
    $stmt = $pdo->prepare('INSERT INTO anggota (nama, no_anggota, alamat, no_hp) VALUES (:nama, :no_anggota, :alamat, :no_hp)');
    $stmt->execute(['nama'=>$nama, 'no_anggota'=>$noAnggota, 'alamat'=>$alamat, 'no_hp'=>$noHp]);
} catch (PDOException $error) {
    if ($error->getCode() === '23505') { setFlash('error', 'No. Anggota sudah terdaftar.'); redirect('tambah.php'); }
    error_log('Gagal menambah anggota: ' . $error->getMessage());
    setFlash('error', 'Anggota gagal ditambahkan.');
    redirect('tambah.php');
}
setFlash('success', 'Anggota berhasil ditambahkan.');
redirect('list.php');
