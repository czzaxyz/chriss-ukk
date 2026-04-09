<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'peminjaman';
include '../../partials/sidebar.php';
include '../../app.php';

// =============================================
// DATA PEMINJAMAN - PAGINATION & SEARCH
// =============================================

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page_current = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page_current - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';

// Query search
$where_conditions = [];
if (!empty($search)) {
    $where_conditions[] = "(p.kode_peminjaman LIKE '%$search%' 
                         OR u.username LIKE '%$search%' 
                         OR b.nama_barang LIKE '%$search%')";
}
$where_sql = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Query total data
$q_total = mysqli_query($connect, "SELECT COUNT(*) as total 
    FROM peminjaman p
    LEFT JOIN users u ON p.user_id = u.id 
    LEFT JOIN barang b ON p.barang_id = b.id 
    $where_sql");
$total_data = mysqli_fetch_assoc($q_total)['total'];
$total_pages = ceil($total_data / $limit);

// Query data peminjaman
$q_peminjaman = mysqli_query($connect, "SELECT p.*, u.username, u.nama_lengkap, b.nama_barang, b.kode_barang
    FROM peminjaman p
    LEFT JOIN users u ON p.user_id = u.id 
    LEFT JOIN barang b ON p.barang_id = b.id 
    $where_sql
    ORDER BY p.id DESC 
    LIMIT $offset, $limit");
?>

<style>
    /* ============================================
   DATA PEMINJAMAN STYLE
   ============================================ */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        font-family: 'Inter', 'Segoe UI', sans-serif;
        overflow-x: hidden;
    }

    #main {
        margin-left: 260px;
        margin-top: 70px;
        padding: 25px 30px;
        width: calc(100% - 260px);
        min-height: calc(100vh - 70px);
        transition: all 0.3s ease;
        background: #f0f2f5;
    }

    @media (max-width: 992px) {
        #main {
            margin-left: 0;
            width: 100%;
            padding: 15px;
        }
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }

    .page-header h2 i {
        color: #667eea;
    }

    .btn-add {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .filter-bar {
        background: white;
        border-radius: 16px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .show-entries {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #4a5568;
    }

    .form-select-sm {
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        font-size: 0.85rem;
        cursor: pointer;
    }

    .search-box {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 6px 12px;
    }

    .search-box i {
        color: #a0aec0;
        margin-right: 8px;
    }

    .search-box input {
        border: none;
        background: transparent;
        padding: 6px 0;
        width: 220px;
        font-size: 0.85rem;
        outline: none;
    }

    .table-container {
        background: white;
        border-radius: 16px;
        overflow-x: auto;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .table-peminjaman {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        min-width: 1000px;
    }

    .table-peminjaman thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 14px 16px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: left;
    }

    .table-peminjaman thead th:first-child {
        border-radius: 12px 0 0 0;
    }

    .table-peminjaman thead th:last-child {
        border-radius: 0 12px 0 0;
    }

    .table-peminjaman tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
        color: #4a5568;
    }

    .table-peminjaman tbody tr:hover {
        background: #f8f9ff;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-disetujui {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-dipinjam {
        background: #d1fae5;
        color: #065f46;
    }

    .status-selesai {
        background: #d1fae5;
        color: #065f46;
    }

    .status-terlambat {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-ditolak {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-detail,
    .btn-approve,
    .btn-return,
    .btn-delete {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-detail {
        background: #4facfe;
        color: white;
    }

    .btn-approve {
        background: #48bb78;
        color: white;
    }

    .btn-return {
        background: #ed8936;
        color: white;
    }

    .btn-delete {
        background: #ff5858;
        color: white;
    }

    .btn-detail:hover,
    .btn-approve:hover,
    .btn-return:hover,
    .btn-delete:hover {
        transform: translateY(-2px);
        opacity: 0.9;
        color: white;
    }

    .pagination-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .data-info {
        font-size: 0.8rem;
        color: #718096;
    }

    .pagination {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .page-link {
        padding: 6px 12px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.8rem;
        color: #4a5568;
        transition: all 0.2s;
    }

    .page-link:hover {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }

    .page-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 15px;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 0.85rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-left: 3px solid #10b981;
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border-left: 3px solid #ef4444;
    }

    @media (max-width: 768px) {
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box input {
            width: 100%;
        }

        .pagination-info {
            flex-direction: column;
            text-align: center;
        }

        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
    }
</style>

<div id="main">
    <div class="page-header">
        <h2><i class="fas fa-handshake me-2"></i> Data Peminjaman</h2>
        <a href="create.php" class="btn-add">
            <i class="fas fa-plus-circle"></i> Tambah Peminjaman
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'];
                                            unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="filter-bar">
        <div class="show-entries">
            <label>Tampilkan:</label>
            <select id="limit-select" class="form-select-sm">
                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            </select>
            <span>data</span>
        </div>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="search-input" placeholder="Cari peminjaman..." value="<?= htmlspecialchars($search) ?>">
        </div>
    </div>

    <div class="table-container">
        <table class="table-peminjaman">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE</th>
                    <th>PEMINJAM</th>
                    <th>MOTOR</th>
                    <th>TGL PINJAM</th>
                    <th>TGL KEMBALI</th>
                    <th>LAMA</th>
                    <th>STATUS</th>
                    <th>TOTAL</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($q_peminjaman) > 0): ?>
                    <?php $no = $offset + 1;
                    while ($row = mysqli_fetch_assoc($q_peminjaman)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><span class="badge-secondary"><?= htmlspecialchars($row['kode_peminjaman']) ?></span></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['nama_barang']) ?> (<?= htmlspecialchars($row['kode_barang']) ?>)</small></td>
                            <td><?= date('d/m/Y', strtotime($row['tgl_pinjam'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                            <td><?= $row['lama_pinjam'] ?> hari</small></td>
                            <td><span class="status-badge status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                            <td>Rp <?= number_format($row['total_harga'] ?? 0, 0, ',', '.') ?></td>
                            <td class="action-buttons">
                                <a href="detail.php?id=<?= $row['id'] ?>" class="btn-detail" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <a href="../../action/peminjaman/approve.php?id=<?= $row['id'] ?>" class="btn-approve" title="Setujui" onclick="return confirm('Setujui peminjaman ini?')">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($row['status'] == 'dipinjam'): ?>
                                    <a href="../../action/peminjaman/return.php?id=<?= $row['id'] ?>" class="btn-return" title="Pengembalian">
                                        <i class="fas fa-undo-alt"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="../../action/peminjaman/destroy.php?id=<?= $row['id'] ?>"
                                    onclick="return confirm('Yakin ingin menghapus peminjaman ini?')"
                                    class="btn-delete" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">
                            <div class="empty-state"><i class="fas fa-handshake"></i>
                                <h4>Belum Ada Data Peminjaman</h4>
                                <p>Klik tombol "Tambah Peminjaman" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_data > 0): ?>
        <div class="pagination-info">
            <div class="data-info">Menampilkan <?= min($offset + 1, $total_data) ?> sampai <?= min($offset + $limit, $total_data) ?> dari <?= $total_data ?> data</div>
            <div class="pagination">
                <?php if ($page_current > 1): ?>
                    <a href="?page=<?= $page_current - 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" class="page-link">&laquo; Sebelumnya</a>
                <?php endif; ?>
                <?php for ($i = max(1, $page_current - 2); $i <= min($total_pages, $page_current + 2); $i++): ?>
                    <a href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" class="page-link <?= $i == $page_current ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($page_current < $total_pages): ?>
                    <a href="?page=<?= $page_current + 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" class="page-link">Selanjutnya &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.getElementById('limit-select')?.addEventListener('change', function() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('limit', this.value);
        urlParams.set('page', 1);
        window.location.href = '?' + urlParams.toString();
    });

    let searchTimeout;
    document.getElementById('search-input')?.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const urlParams = new URLSearchParams(window.location.search);
            if (this.value) urlParams.set('search', this.value);
            else urlParams.delete('search');
            urlParams.set('page', 1);
            window.location.href = '?' + urlParams.toString();
        }, 500);
    });
</script>

<?php include '../../partials/footer.php';
include '../../partials/script.php'; ?>