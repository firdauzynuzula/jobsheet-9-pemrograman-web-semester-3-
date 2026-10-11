<?php
session_start();
require_once __DIR__ . '/helpers.php';
$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
$page_title = $page_title ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPUS-Mini<?php echo $page_title !== '' ? ' | ' . e($page_title) : ''; ?></title>
    <link rel="stylesheet" href="<?php echo e($base); ?>assets/css/style.css">
</head>
<body>
<header>
    <h1>SIMPUS-Mini</h1>
    <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu" aria-expanded="false">&#9776;</button>
    <nav>
        <ul>
            <li><a href="<?php echo e($base); ?>index.php">Beranda</a></li>
            <li><a href="<?php echo e($base); ?>buku/list.php">Daftar Buku</a></li>
            <li><a href="<?php echo e($base); ?>buku/tambah.php">Tambah Buku</a></li>
            <li><a href="<?php echo e($base); ?>anggota/list.php">Daftar Anggota</a></li>
            <li><a href="<?php echo e($base); ?>anggota/tambah.php">Tambah Anggota</a></li>
        </ul>
    </nav>
</header>
<main>
