<?php
$page_title = 'Daftar Buku';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$perPage = 10;
$page = max(1, (int) ($_GET['page'] ?? 1));
$keyword = trim($_GET['q'] ?? '');
$search = $keyword === '' ? '' : '%' . $keyword . '%';

if ($keyword === '') {
    $totalRows = (int) $pdo->query('SELECT COUNT(*) FROM buku')->fetchColumn();
    $stmt = $pdo->prepare('SELECT id, judul, pengarang, tahun, stok FROM buku ORDER BY id DESC LIMIT :limit OFFSET :offset');
} else {
    $count = $pdo->prepare('SELECT COUNT(*) FROM buku WHERE judul ILIKE :search_title OR pengarang ILIKE :search_author');
    $count->execute(['search_title' => $search, 'search_author' => $search]);
    $totalRows = (int) $count->fetchColumn();
    $stmt = $pdo->prepare('SELECT id, judul, pengarang, tahun, stok FROM buku WHERE judul ILIKE :search_title OR pengarang ILIKE :search_author ORDER BY id DESC LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':search_title', $search, PDO::PARAM_STR);
    $stmt->bindValue(':search_author', $search, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
$stmt->execute();
$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
if ($page > $totalPages) {
    redirect('list.php?' . http_build_query(array_filter(['q' => $keyword, 'page' => $totalPages], fn ($value) => $value !== '')));
}
?>
<section>
    <h2>Daftar Buku</h2>
    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p>
    <?php endif; ?>
    <div class="search-box">
        <form method="get" action="list.php">
            <div><label for="search-input">Cari Buku</label><input type="search" id="search-input" name="q" value="<?php echo e($keyword); ?>" placeholder="Judul atau pengarang"></div>
            <button type="submit">Cari</button>
        </form>
    </div>
    <div class="table-responsive">
        <table data-search-column="0">
            <thead><tr><th>Judul</th><th>Pengarang</th><th>Tahun</th><th>Stok</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php if (!$daftarBuku): ?>
                <tr><td colspan="5">Belum ada data buku.</td></tr>
            <?php else: foreach ($daftarBuku as $buku): ?>
                <tr>
                    <td><?php echo e($buku['judul']); ?></td>
                    <td><?php echo e($buku['pengarang']); ?></td>
                    <td><?php echo e($buku['tahun']); ?></td>
                    <td><?php echo e($buku['stok']); ?></td>
                    <td>
                        <a class="btn-edit" href="edit.php?id=<?php echo e($buku['id']); ?>">Edit</a>
                        <form class="form-hapus" method="post" action="hapus.php">
                            <input type="hidden" name="id" value="<?php echo e($buku['id']); ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($totalPages > 1): ?>
    <nav class="pagination" aria-label="Pagination">
        <?php for ($number = 1; $number <= $totalPages; $number++): $params = ['page' => $number]; if ($keyword !== '') $params['q'] = $keyword; ?>
            <a class="<?php echo $number === $page ? 'active' : ''; ?>" href="list.php?<?php echo e(http_build_query($params)); ?>"><?php echo $number; ?></a>
        <?php endfor; ?>
    </nav>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
