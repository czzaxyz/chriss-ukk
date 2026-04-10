<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'motor';
include '../../partials/sidebar.php';
include '../../app.php';

// =============================================
// DATA MOTOR - PAGINATION & SEARCH
// =============================================

// Konfigurasi pagination
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page_current = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page_current - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';

// Query search & filter
$where_conditions = [];
if (!empty($search)) {
    $where_conditions[] = "(b.nama_barang LIKE '%$search%' 
                         OR b.kode_barang LIKE '%$search%' 
                         OR b.merk LIKE '%$search%'
                         OR k.nama_kategori LIKE '%$search%')";
}
$where_sql = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Query total data
$q_total = mysqli_query($connect, "SELECT COUNT(*) as total 
    FROM barang b 
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    $where_sql");
$total_data = mysqli_fetch_assoc($q_total)['total'];
$total_pages = ceil($total_data / $limit);

// Query data motor
$q_motor = mysqli_query($connect, "SELECT b.*, k.nama_kategori 
    FROM barang b
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    $where_sql
    ORDER BY b.id DESC 
    LIMIT $offset, $limit");
?>

<style>
/* ============================================
   DATA MOTOR STYLE - MODERN & PREMIUM
   ============================================ */

/* Google Fonts */
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

/* ============================================
   MAIN CONTENT
   ============================================ */
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

/* ============================================
   PAGE HEADER
   ============================================ */
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

/* ============================================
   FILTER BAR
   ============================================ */
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

/* ============================================
   TABLE MOTOR
   ============================================ */
.table-container {
    background: white;
    border-radius: 16px;
    overflow-x: auto;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.table-motor {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    min-width: 900px;
}

.table-motor thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 14px 16px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
}

.table-motor thead th:first-child {
    border-radius: 12px 0 0 0;
}

.table-motor thead th:last-child {
    border-radius: 0 12px 0 0;
}

.table-motor tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #f0f2f5;
    vertical-align: middle;
    color: #4a5568;
}

.table-motor tbody tr:hover {
    background: #f8f9ff;
}

/* Status Badge */
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-block;
}

.status-tersedia {
    background: #d1fae5;
    color: #065f46;
}

.status-dipinjam {
    background: #fef3c7;
    color: #92400e;
}

.status-rusak {
    background: #fee2e2;
    color: #991b1b;
}

.status-hilang {
    background: #f1f5f9;
    color: #475569;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-detail {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.7rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-edit {
    background: linear-gradient(135deg, #fad961 0%, #f76b1c 100%);
    color: white;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.7rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-delete {
    background: linear-gradient(135deg, #ff5858 0%, #f09819 100%);
    color: white;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.7rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-detail:hover, .btn-edit:hover, .btn-delete:hover {
    transform: translateY(-2px);
    opacity: 0.9;
    color: white;
}

/* Badge */
.badge-secondary {
    background: #e2e8f0;
    color: #4a5568;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 500;
}

/* ============================================
   PAGINATION
   ============================================ */
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

.page-dots {
    padding: 6px 8px;
    color: #a0aec0;
}

.text-center {
    text-align: center;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 15px;
}

.empty-state h4 {
    font-size: 1.2rem;
    color: #4a5568;
    margin-bottom: 8px;
}

.empty-state p {
    color: #a0aec0;
}

/* Alert */
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

/* Responsive */
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
    <!-- Header -->
    <div class="page-header">
        <h2><i class="fas fa-motorcycle me-2"></i> Data Motor</h2>
        <a href="create.php" class="btn-add">
            <i class="fas fa-plus-circle"></i> Tambah Motor
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Filter & Search -->
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
            <input type="text" id="search-input" placeholder="Cari motor..." value="<?= htmlspecialchars($search) ?>">
        </div>
    </div>

    <!-- Tabel Data Motor -->
    <div class="table-container">
        <table class="table-motor">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE</th>
                    <th>NAMA MOTOR</th>
                    <th>KATEGORI</th>
                    <th>MERK</th>
                    <th>TAHUN</th>
                    <th>STOK</th>
                    <th>STATUS</th>
                    <th>HARGA/HARI</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($q_motor) > 0): ?>
                    <?php
                    $no = $offset + 1;
                    while ($row = mysqli_fetch_assoc($q_motor)):
                        $status_class = '';
                        switch ($row['status']) {
                            case 'tersedia': $status_class = 'status-tersedia'; break;
                            case 'dipinjam': $status_class = 'status-dipinjam'; break;
                            case 'rusak': $status_class = 'status-rusak'; break;
                            case 'hilang': $status_class = 'status-hilang'; break;
                            default: $status_class = 'status-tersedia';
                        }
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><span class="badge-secondary"><?= htmlspecialchars($row['kode_barang']) ?></span></td>
                            <td><strong><?= htmlspecialchars($row['nama_barang']) ?></strong></td>
                            <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['merk'] ?? '-') ?></td>
                            <td><?= $row['tahun'] ?? '-' ?></td>
                            <td><?= $row['stok'] ?? 0 ?></td>
                            <td><span class="status-badge <?= $status_class ?>"><?= ucfirst($row['status']) ?></span></td>
                            <td>Rp <?= number_format($row['harga_sewa_perhari'] ?? 0, 0, ',', '.') ?></td>
                            <td class="action-buttons">
                                <a href="detail.php?id=<?= $row['id'] ?>" class="btn-detail" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="../../action/motor/destroy.php?id=<?= $row['id'] ?>" 
                                   onclick="return confirm('Yakin ingin menghapus motor <?= addslashes($row['nama_barang']) ?>?')"
                                   class="btn-delete" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <i class="fas fa-motorcycle"></i>
                                <h4>Belum Ada Data Motor</h4>
                                <p>Klik tombol "Tambah Motor" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination & Info -->
    <?php if ($total_data > 0): ?>
    <div class="pagination-info">
        <div class="data-info">
            Menampilkan <?= min($offset + 1, $total_data) ?> sampai <?= min($offset + $limit, $total_data) ?> dari <?= $total_data ?> data
        </div>
        <div class="pagination">
            <?php if ($page_current > 1): ?>
                <a href="?page=<?= $page_current - 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" class="page-link">&laquo; Sebelumnya</a>
            <?php endif; ?>

            <?php
            $start_page = max(1, $page_current - 2);
            $end_page = min($total_pages, $page_current + 2);

            if ($start_page > 1): ?>
                <a href="?page=1&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" class="page-link">1</a>
                <?php if ($start_page > 2): ?>
                    <span class="page-dots">...</span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                <a href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" class="page-link <?= $i == $page_current ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($end_page < $total_pages): ?>
                <?php if ($end_page < $total_pages - 1): ?>
                    <span class="page-dots">...</span>
                <?php endif; ?>
                <a href="?page=<?= $total_pages ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" class="page-link"><?= $total_pages ?></a>
            <?php endif; ?>

            <?php if ($page_current < $total_pages): ?>
                <a href="?page=<?= $page_current + 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" class="page-link">Selanjutnya &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    // Pagination dengan JavaScript
    document.getElementById('limit-select')?.addEventListener('change', function() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('limit', this.value);
        urlParams.set('page', 1);
        window.location.href = '?' + urlParams.toString();
    });

    // Search dengan debounce
    let searchTimeout;
    document.getElementById('search-input')?.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const urlParams = new URLSearchParams(window.location.search);
            if (this.value) {
                urlParams.set('search', this.value);
            } else {
                urlParams.delete('search');
            }
            urlParams.set('page', 1);
            window.location.href = '?' + urlParams.toString();
        }, 500);
    });
</script>

<?php include '../../partials/footer.php';
include '../../partials/script.php'; ?>