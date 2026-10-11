<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/helpers.php';
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) redirect('list.php');
$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');
if ($nama === '' || $noAnggota === '') { setFlash('error', 'Nama dan No. Anggota wajib diisi.'); redirect('edit.php?id=' . $id); }
try {
    $stmt = $pdo->prepare('UPDATE anggota SET nama=:nama, no_anggota=:no_anggota, alamat=:alamat, no_hp=:no_hp WHERE id=:id');
    $stmt->execute(['nama'=>$nama, 'no_anggota'=>$noAnggota, 'alamat'=>$alamat, 'no_hp'=>$noHp, 'id'=>$id]);
} catch (PDOException $error) {
    if ($error->getCode() === '23505') { setFlash('error', 'No. Anggota sudah terdaftar.'); redirect('edit.php?id=' . $id); }
    error_log('Gagal mengubah anggota: ' . $error->getMessage());
    setFlash('error', 'Anggota gagal diperbarui.');
    redirect('edit.php?id=' . $id);
}
setFlash('success', 'Anggota berhasil diperbarui.');
redirect('list.php');
