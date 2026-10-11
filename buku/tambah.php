<?php
$page_title = 'Tambah Buku';
include __DIR__ . '/../includes/header.php';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section>
    <h2>Tambah Buku</h2>
    <?php if ($flash): ?><p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p><?php endif; ?>
    <form id="form-tambah" method="post" action="proses_tambah.php">
        <p><label for="judul">Judul</label><input id="judul" type="text" name="judul" required></p>
        <p><label for="pengarang">Pengarang</label><input id="pengarang" type="text" name="pengarang" required></p>
        <p><label for="tahun">Tahun</label><input id="tahun" type="number" name="tahun" min="1900" max="<?php echo date('Y'); ?>" required></p>
        <p><label for="isbn">ISBN</label><input id="isbn" type="text" name="isbn"></p>
        <p><label for="stok">Stok</label><input id="stok" type="number" name="stok" min="0" required></p>
        <p><label for="kategori">Kategori</label><input id="kategori" type="text" name="kategori"></p>
        <p><button type="submit">Simpan</button></p>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
