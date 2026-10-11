<?php
$page_title = 'Edit Anggota';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/helpers.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) redirect('list.php');
$stmt = $pdo->prepare('SELECT * FROM anggota WHERE id = :id');
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$anggota) redirect('list.php');
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section><h2>Edit Anggota</h2>
<?php if ($flash): ?><p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p><?php endif; ?>
<form id="form-tambah" method="post" action="proses_edit.php">
<input type="hidden" name="id" value="<?php echo e($anggota['id']); ?>">
<p><label for="nama">Nama</label><input id="nama" type="text" name="nama" value="<?php echo e($anggota['nama']); ?>" required></p>
<p><label for="no_anggota">No. Anggota</label><input id="no_anggota" type="text" name="no_anggota" value="<?php echo e($anggota['no_anggota']); ?>" required></p>
<p><label for="alamat">Alamat</label><input id="alamat" type="text" name="alamat" value="<?php echo e($anggota['alamat']); ?>"></p>
<p><label for="no_hp">No. HP</label><input id="no_hp" type="text" name="no_hp" value="<?php echo e($anggota['no_hp']); ?>"></p>
<p><button type="submit">Simpan Perubahan</button> <a class="btn-batal" href="list.php">Batal</a></p>
</form></section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
