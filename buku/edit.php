<?php
$page_title = 'Edit Buku';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/helpers.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) redirect('list.php');
$stmt = $pdo->prepare('SELECT * FROM buku WHERE id = :id');
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$buku) redirect('list.php');
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section>
    <h2>Edit Buku</h2>
    <?php if ($flash): ?><p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p><?php endif; ?>
    <form id="form-tambah" method="post" action="proses_edit.php">
        <input type="hidden" name="id" value="<?php echo e($buku['id']); ?>">
        <p><label for="judul">Judul</label><input id="judul" type="text" name="judul" value="<?php echo e($buku['judul']); ?>" required></p>
        <p><label for="pengarang">Pengarang</label><input id="pengarang" type="text" name="pengarang" value="<?php echo e($buku['pengarang']); ?>" required></p>
        <p><label for="tahun">Tahun</label><input id="tahun" type="number" name="tahun" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo e($buku['tahun']); ?>" required></p>
        <p><label for="isbn">ISBN</label><input id="isbn" type="text" name="isbn" value="<?php echo e($buku['isbn']); ?>"></p>
        <p><label for="stok">Stok</label><input id="stok" type="number" name="stok" min="0" value="<?php echo e($buku['stok']); ?>" required></p>
        <p><label for="kategori">Kategori</label><input id="kategori" type="text" name="kategori" value="<?php echo e($buku['kategori']); ?>"></p>
        <p><button type="submit">Simpan Perubahan</button> <a class="btn-batal" href="list.php">Batal</a></p>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
