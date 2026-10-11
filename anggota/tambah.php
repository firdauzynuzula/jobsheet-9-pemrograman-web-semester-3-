<?php
$page_title = 'Tambah Anggota';
include __DIR__ . '/../includes/header.php';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section><h2>Tambah Anggota</h2>
<?php if ($flash): ?><p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p><?php endif; ?>
<form id="form-tambah" method="post" action="proses_tambah.php">
<p><label for="nama">Nama</label><input id="nama" type="text" name="nama" required></p>
<p><label for="no_anggota">No. Anggota</label><input id="no_anggota" type="text" name="no_anggota" required></p>
<p><label for="alamat">Alamat</label><input id="alamat" type="text" name="alamat"></p>
<p><label for="no_hp">No. HP</label><input id="no_hp" type="text" name="no_hp"></p>
<p><button type="submit">Simpan</button></p>
</form></section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
