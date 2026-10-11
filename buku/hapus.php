<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/helpers.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('list.php');
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) redirect('list.php');
$stmt = $pdo->prepare('DELETE FROM buku WHERE id = :id');
$stmt->execute(['id' => $id]);
setFlash('success', $stmt->rowCount() ? 'Buku berhasil dihapus.' : 'Buku tidak ditemukan.');
redirect('list.php');
