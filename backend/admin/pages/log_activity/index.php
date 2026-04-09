<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'log_activity';
include '../../partials/sidebar.php';
include '../../app.php';

// =============================================
// LOG ACTIVITY - PAGINATION & SEARCH
// =============================================

// Konfigurasi pagination
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page_current = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page_current - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$filter_role = isset($_GET['role']) ? mysqli_real_escape_string($connect, $_GET['role']) : '';

// Query search & filter
$where_conditions = [];
if (!empty($search)) {
    $where_conditions[] = "(username LIKE '%$search%' OR aktivitas LIKE '%$search%' OR role LIKE '%$search%')";
}
if (!empty($filter_role)) {
    $where_conditions[] = "role = '$filter_role'";
}
$where_sql = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Query total data
$q_total = mysqli_query($connect, "SELECT COUNT(*) as total FROM log_aktivitas $where_sql");
$total_data = mysqli_fetch_assoc($q_total)['total'];
$total_pages = ceil($total_data / $limit);

// Query data log activity
$q_log = mysqli_query($connect, "SELECT * FROM log_aktivitas 
    $where_sql
    ORDER BY id_log DESC 
    LIMIT $offset, $limit");

// Ambil daftar role untuk filter
$q_roles = mysqli_query($connect, "SELECT DISTINCT role FROM log_aktivitas ORDER BY role");
$roles = [];
while ($row = mysqli_fetch_assoc($q_roles)) {
    $roles[] = $row['role'];
}
?>

<style>
/* ============================================
   LOG ACTIVITY STYLE - MODERN & PREMIUM
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

.filter-role {
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-role select {
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
   TABLE LOG ACTIVITY
   ============================================ */
.table-container {
    background: white;
    border-radius: 16px;
    overflow-x: auto;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.table-log {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    min-width: 800px;
}

.table-log thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 14px 16px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
}

.table-log thead th:first-child {
    border-radius: 12px 0 0 0;
}

.table-log thead th:last-child {
    border-radius: 0 12px 0 0;
}

.table-log tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #f0f2f5;
    vertical-align: middle;
    color: #4a5568;
}

.table-log tbody tr:hover {
    background: #f8f9ff;
}

/* Role Badge */
.role-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-block;
}

.role-admin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.role-petugas {
    background: linear-gradient(135deg, #fad961 0%, #f76b1c 100%);
    color: white;
}

.role-peminjam {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

/* Aktivitas Text */
.aktivitas-text {
    max-width: 400px;
    word-break: break-word;
}

/* Waktu */
.waktu-text {
    font-size: 0.75rem;
    white-space: nowrap;
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
    
    .aktivitas-text {
        max-width: 200px;
    }
}
</style>

<div id="main">
    <!-- Header -->
    <div class="page-header">
        <h2><i class="fas fa-history me-2"></i> Log Activity</h2>
    </div>

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
        <div class="filter-role">
            <label>Filter Role:</label>
            <select id="role-filter">
                <option value="">Semua Role</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= htmlspecialchars($role) ?>" <?= $filter_role == $role ? 'selected' : '' ?>>
                        <?= ucfirst($role) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="search-input" placeholder="Cari user atau aktivitas..." value="<?= htmlspecialchars($search) ?>">
        </div>
    </div>

    <!-- Tabel Log Activity -->
    <div class="table-container">
        <table class="table-log">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>USERNAME</th>
                    <th>ROLE</th>
                    <th>AKTIVITAS</th>
                    <th>WAKTU</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($q_log) > 0): ?>
                    <?php
                    $no = $offset + 1;
                    while ($row = mysqli_fetch_assoc($q_log)):
                        // Menentukan class role badge
                        $role_class = '';
                        switch ($row['role']) {
                            case 'admin':
                                $role_class = 'role-admin';
                                break;
                            case 'petugas':
                                $role_class = 'role-petugas';
                                break;
                            case 'peminjam':
                                $role_class = 'role-peminjam';
                                break;
                            default:
                                $role_class = 'role-peminjam';
                        }
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= htmlspecialchars($row['username']) ?></strong></td>
                            <td><span class="role-badge <?= $role_class ?>"><?= ucfirst($row['role']) ?></span></td>
                            <td class="aktivitas-text"><?= htmlspecialchars($row['aktivitas']) ?></td>
                            <td class="waktu-text">
                                <?= date('d/m/Y H:i:s', strtotime($row['waktu'])) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-history"></i>
                                <h4>Belum Ada Log Activity</h4>
                                <p>Belum ada aktivitas yang tercatat di sistem</p>
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
                <a href="?page=<?= $page_current - 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($filter_role) ?>" class="page-link">&laquo; Sebelumnya</a>
            <?php endif; ?>

            <?php
            $start_page = max(1, $page_current - 2);
            $end_page = min($total_pages, $page_current + 2);

            if ($start_page > 1): ?>
                <a href="?page=1&limit=<?= $limit ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($filter_role) ?>" class="page-link">1</a>
                <?php if ($start_page > 2): ?>
                    <span class="page-dots">...</span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                <a href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($filter_role) ?>" class="page-link <?= $i == $page_current ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($end_page < $total_pages): ?>
                <?php if ($end_page < $total_pages - 1): ?>
                    <span class="page-dots">...</span>
                <?php endif; ?>
                <a href="?page=<?= $total_pages ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($filter_role) ?>" class="page-link"><?= $total_pages ?></a>
            <?php endif; ?>

            <?php if ($page_current < $total_pages): ?>
                <a href="?page=<?= $page_current + 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($filter_role) ?>" class="page-link">Selanjutnya &raquo;</a>
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

    // Filter role
    document.getElementById('role-filter')?.addEventListener('change', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (this.value) {
            urlParams.set('role', this.value);
        } else {
            urlParams.delete('role');
        }
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