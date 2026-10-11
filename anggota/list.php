<?php
$page_title = 'Daftar Anggota';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$perPage = 10;
$page = max(1, (int) ($_GET['page'] ?? 1));
$keyword = trim($_GET['q'] ?? '');
$search = '%' . $keyword . '%';
if ($keyword === '') {
    $totalRows = (int) $pdo->query('SELECT COUNT(*) FROM anggota')->fetchColumn();
    $stmt = $pdo->prepare('SELECT id, no_anggota, nama, alamat, no_hp FROM anggota ORDER BY id DESC LIMIT :limit OFFSET :offset');
} else {
    $count = $pdo->prepare('SELECT COUNT(*) FROM anggota WHERE nama ILIKE :search');
    $count->execute(['search' => $search]);
    $totalRows = (int) $count->fetchColumn();
    $stmt = $pdo->prepare('SELECT id, no_anggota, nama, alamat, no_hp FROM anggota WHERE nama ILIKE :search ORDER BY id DESC LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':search', $search, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
$stmt->execute();
$daftarAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
if ($page > $totalPages) redirect('list.php?' . http_build_query(array_filter(['q' => $keyword, 'page' => $totalPages], fn ($value) => $value !== '')));
?>
<section>
    <h2>Daftar Anggota</h2>
    <?php if ($flash): ?><p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p><?php endif; ?>
    <div class="search-box"><form method="get" action="list.php"><div><label for="search-input">Cari Nama Anggota</label><input type="search" id="search-input" name="q" value="<?php echo e($keyword); ?>" placeholder="Ketik nama anggota"></div><button type="submit">Cari</button></form></div>
    <div class="table-responsive"><table data-search-column="1"><thead><tr><th>No. Anggota</th><th>Nama</th><th>Alamat</th><th>No. HP</th><th>Aksi</th></tr></thead><tbody>
    <?php if (!$daftarAnggota): ?><tr><td colspan="5">Belum ada data anggota.</td></tr>
    <?php else: foreach ($daftarAnggota as $anggota): ?><tr>
        <td><?php echo e($anggota['no_anggota']); ?></td><td><?php echo e($anggota['nama']); ?></td><td><?php echo e($anggota['alamat']); ?></td><td><?php echo e($anggota['no_hp']); ?></td>
        <td><a class="btn-edit" href="edit.php?id=<?php echo e($anggota['id']); ?>">Edit</a><form class="form-hapus" method="post" action="hapus.php"><input type="hidden" name="id" value="<?php echo e($anggota['id']); ?>"><button type="submit">Hapus</button></form></td>
    </tr><?php endforeach; endif; ?></tbody></table></div>
    <?php if ($totalPages > 1): ?><nav class="pagination" aria-label="Pagination"><?php for ($number=1; $number <= $totalPages; $number++): $params=['page'=>$number]; if ($keyword !== '') $params['q']=$keyword; ?><a class="<?php echo $number === $page ? 'active' : ''; ?>" href="list.php?<?php echo e(http_build_query($params)); ?>"><?php echo $number; ?></a><?php endfor; ?></nav><?php endif; ?>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
