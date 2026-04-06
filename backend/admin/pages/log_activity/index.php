<?php
// AKTIFKAN ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan session_start() di awal
session_start();

// Include koneksi database
include '../../app.php';

// Query log aktivitas dengan filter untuk login
$qLogAktivitas = "SELECT 
        la.*,
        u.username,
        u.role
    FROM log_aktivitas la
    LEFT JOIN users u ON la.user_id = u.id
    WHERE la.aksi LIKE '%login%' OR la.aksi LIKE '%logout%' OR la.aksi LIKE '%auth%'
    ORDER BY la.created_at DESC
    LIMIT 100";
                
$result = mysqli_query($connect, $qLogAktivitas);

if (!$result) {
    die("Query error: " . mysqli_error($connect));
}

// Simpan data ke array
$logs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $logs[] = $row;
}

// Hitung total data
$totalLogs = count($logs);

// Hitung statistik login
$statistics = [
    'total_logs' => $totalLogs,
    'today_logs' => 0,
    'success_login' => 0,
    'failed_login' => 0,
    'unique_users' => 0
];

// Array untuk menyimpan user yang sudah dihitung
$user_ids = [];

foreach ($logs as $item) {
    // Hitung log hari ini
    $logDate = date('Y-m-d', strtotime($item['created_at']));
    $today = date('Y-m-d');
    if ($logDate == $today) {
        $statistics['today_logs']++;
    }
    
    // Hitung login sukses/gagal
    if (stripos($item['aksi'], 'berhasil') !== false || stripos($item['aksi'], 'success') !== false) {
        $statistics['success_login']++;
    } elseif (stripos($item['aksi'], 'gagal') !== false || stripos($item['aksi'], 'failed') !== false) {
        $statistics['failed_login']++;
    }
    
    // Hitung user unik
    if ($item['user_id'] && !in_array($item['user_id'], $user_ids)) {
        $user_ids[] = $item['user_id'];
        $statistics['unique_users']++;
    }
}

// Query untuk chart (login per hari 7 hari terakhir)
$qChartData = "SELECT 
        DATE(created_at) as tanggal,
        COUNT(*) as jumlah,
        SUM(CASE WHEN aksi LIKE '%berhasil%' OR aksi LIKE '%success%' THEN 1 ELSE 0 END) as berhasil,
        SUM(CASE WHEN aksi LIKE '%gagal%' OR aksi LIKE '%failed%' THEN 1 ELSE 0 END) as gagal
    FROM log_aktivitas 
    WHERE (aksi LIKE '%login%' OR aksi LIKE '%logout%' OR aksi LIKE '%auth%')
        AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY tanggal DESC";
$resultChart = mysqli_query($connect, $qChartData);

$chartLabels = [];
$chartDataTotal = [];
$chartDataSuccess = [];
$chartDataFailed = [];

if ($resultChart && mysqli_num_rows($resultChart) > 0) {
    while ($row = mysqli_fetch_assoc($resultChart)) {
        $chartLabels[] = date('d M', strtotime($row['tanggal']));
        $chartDataTotal[] = $row['jumlah'];
        $chartDataSuccess[] = $row['berhasil'];
        $chartDataFailed[] = $row['gagal'];
    }
    // Reverse untuk urutan ascending
    $chartLabels = array_reverse($chartLabels);
    $chartDataTotal = array_reverse($chartDataTotal);
    $chartDataSuccess = array_reverse($chartDataSuccess);
    $chartDataFailed = array_reverse($chartDataFailed);
}

// Query untuk user dengan login terbanyak - PERBAIKI INI
$qTopUsers = "SELECT 
        u.id,
        u.username,
        u.role,
        (SELECT COUNT(*) FROM log_aktivitas la 
         WHERE la.user_id = u.id 
         AND (la.aksi LIKE '%login%' OR la.aksi LIKE '%logout%' OR la.aksi LIKE '%auth%')
        ) as total_login
    FROM users u
    WHERE EXISTS (
        SELECT 1 FROM log_aktivitas la 
        WHERE la.user_id = u.id 
        AND (la.aksi LIKE '%login%' OR la.aksi LIKE '%logout%' OR la.aksi LIKE '%auth%')
    )
    ORDER BY total_login DESC
    LIMIT 10";
    
$resultTopUsers = mysqli_query($connect, $qTopUsers);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas Login - Admin Panel</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- ApexCharts CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.css">
    
    <style>
        /* ===== GLOBAL STYLING ===== */
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sidebar adjustment */
        #main {
            margin-left: 260px;
            margin-top: 70px;
            padding: 25px;
            width: calc(100% - 260px);
            min-height: calc(100vh - 70px);
            transition: all 0.3s ease;
            background-color: #f8f9fc;
        }

        @media (max-width: 768px) {
            #main {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }
        }

        /* Card styling */
        .main-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            overflow: hidden;
        }

        .card-header-custom {
            background: #ffffff;
            border-bottom: 2px solid #f0f2f5;
            padding: 20px 30px;
        }

        .card-body-custom {
            padding: 30px;
        }

        /* Header styling */
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #4e73df;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 1.5rem;
            color: white;
        }

        .stats-icon.total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stats-icon.today {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .stats-icon.success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .stats-icon.failed {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        }

        .stats-content h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #2d3748;
        }

        .stats-content p {
            color: #718096;
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            margin-bottom: 30px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        /* Log Item Styling */
        .log-item {
            display: flex;
            align-items: flex-start;
            padding: 15px;
            border-bottom: 1px solid #f0f2f5;
            transition: all 0.2s ease;
        }

        .log-item:last-child {
            border-bottom: none;
        }

        .log-item:hover {
            background: #f8f9ff;
            border-radius: 10px;
        }

        .log-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }

        .log-icon.login {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .log-icon.logout {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .log-icon.failed {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        }

        .log-icon.attempt {
            background: linear-gradient(135deg, #ffd700 0%, #ffa500 100%);
        }

        .log-content {
            flex-grow: 1;
        }

        .log-user {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 3px;
        }

        .log-action {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .log-details {
            font-size: 0.85rem;
            color: #a0aec0;
        }

        .log-time {
            text-align: right;
            font-size: 0.85rem;
            color: #718096;
            white-space: nowrap;
            margin-left: 15px;
        }

        .log-ip {
            display: inline-block;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        /* Status badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-right: 8px;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Top Users Card */
        .top-users-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            height: 100%;
        }

        .user-rank {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: white;
            margin-right: 15px;
        }

        .rank-1 { background: linear-gradient(135deg, #ffd700 0%, #ffa500 100%); }
        .rank-2 { background: linear-gradient(135deg, #c0c0c0 0%, #a9a9a9 100%); }
        .rank-3 { background: linear-gradient(135deg, #cd7f32 0%, #b87333 100%); }
        .rank-other { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }

        /* Filter section */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
        }

        /* No data state */
        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }

        .no-data i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .no-data h5 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header-custom {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .log-item {
                flex-direction: column;
            }
            
            .log-time {
                text-align: left;
                margin-left: 0;
                margin-top: 10px;
            }
            
            .log-icon {
                margin-bottom: 10px;
            }
            
            .stats-card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

<?php 
include '../../partials/header.php'; 
$page = 'log_activity'; 
include '../../partials/sidebar.php'; 
?>

<div class="container-fluid">
    <div id="main">
        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div class="card-header-custom d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <i class="fas fa-history"></i>
                    Log Aktivitas Login
                </h2>
                <button class="btn btn-primary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </button>
            </div>
            
            <!-- Card Body -->
            <div class="card-body-custom">
                <!-- Session Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon total">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="stats-content">
                                <h3><?php echo number_format($statistics['total_logs']); ?></h3>
                                <p>Total Log Aktivitas</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon today">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="stats-content">
                                <h3><?php echo number_format($statistics['today_logs']); ?></h3>
                                <p>Login Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stats-content">
                                <h3><?php echo number_format($statistics['success_login']); ?></h3>
                                <p>Login Berhasil</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon failed">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="stats-content">
                                <h3><?php echo number_format($statistics['failed_login']); ?></h3>
                                <p>Login Gagal</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Chart Section -->
                <div class="chart-container">
                    <div class="chart-header">
                        <h3>Statistik Login 7 Hari Terakhir</h3>
                    </div>
                    <div id="loginChart" style="height: 350px;"></div>
                </div>
                
                <!-- Top Users and Recent Activity -->
                <div class="row mb-4">
                    <!-- Top Users -->
                    <div class="col-lg-4 mb-4">
                        <div class="top-users-card">
                            <h4 class="mb-4">
                                <i class="fas fa-trophy me-2"></i>
                                Top 10 User Aktif
                            </h4>
                            <?php if ($resultTopUsers && mysqli_num_rows($resultTopUsers) > 0): ?>
                                <?php $rank = 1; ?>
                                <?php while ($user = mysqli_fetch_assoc($resultTopUsers)): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="user-rank rank-<?php echo $rank <= 3 ? $rank : 'other'; ?>">
                                        <?php echo $rank; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold"><?php echo htmlspecialchars($user['username']); ?></div>
                                        <small class="text-muted"><?php echo ucfirst($user['role']); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary"><?php echo $user['total_login']; ?> login</span>
                                    </div>
                                </div>
                                <?php $rank++; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada data user dengan aktivitas login</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Recent Login Activity -->
                    <div class="col-lg-8">
                        <div class="filter-section">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <select id="filterUser" class="form-select">
                                        <option value="">Semua User</option>
                                        <?php foreach ($user_ids as $uid): 
                                            $userQuery = mysqli_query($connect, "SELECT username FROM users WHERE id = $uid");
                                            if ($userRow = mysqli_fetch_assoc($userQuery)): ?>
                                            <option value="<?php echo $uid; ?>">
                                                <?php echo htmlspecialchars($userRow['username']); ?>
                                            </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <select id="filterStatus" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="berhasil">Berhasil</option>
                                        <option value="gagal">Gagal</option>
                                        <option value="logout">Logout</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="date" id="filterDate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="searchLogs" class="form-control" placeholder="Cari berdasarkan username, aksi, atau IP...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="main-card">
                            <div class="card-header-custom d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-clock me-2"></i>
                                    Aktivitas Login Terbaru (<?php echo $totalLogs; ?> log)
                                </h5>
                                <button class="btn btn-sm btn-outline-secondary" onclick="resetFilters()">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </button>
                            </div>
                            <div class="card-body-custom">
                                <?php if ($totalLogs > 0): ?>
                                    <div id="logList">
                                        <?php foreach ($logs as $log): 
                                            // Determine icon and status
                                            $iconClass = 'login';
                                            $statusBadge = 'badge-success';
                                            $statusText = 'Berhasil';
                                            
                                            if (stripos($log['aksi'], 'logout') !== false) {
                                                $iconClass = 'logout';
                                                $statusBadge = 'badge-info';
                                                $statusText = 'Logout';
                                            } elseif (stripos($log['aksi'], 'gagal') !== false || stripos($log['aksi'], 'failed') !== false) {
                                                $iconClass = 'failed';
                                                $statusBadge = 'badge-failed';
                                                $statusText = 'Gagal';
                                            } elseif (stripos($log['aksi'], 'attempt') !== false) {
                                                $iconClass = 'attempt';
                                                $statusBadge = 'badge-info';
                                                $statusText = 'Percobaan';
                                            }
                                        ?>
                                        <div class="log-item" data-user-id="<?php echo $log['user_id']; ?>" 
                                             data-status="<?php echo strtolower($statusText); ?>"
                                             data-date="<?php echo date('Y-m-d', strtotime($log['created_at'])); ?>"
                                             data-search="<?php echo htmlspecialchars(strtolower($log['username'] . ' ' . $log['aksi'] . ' ' . $log['ip_address'])); ?>">
                                            <div class="log-icon <?php echo $iconClass; ?>">
                                                <?php if ($iconClass == 'login'): ?>
                                                    <i class="fas fa-sign-in-alt"></i>
                                                <?php elseif ($iconClass == 'logout'): ?>
                                                    <i class="fas fa-sign-out-alt"></i>
                                                <?php elseif ($iconClass == 'failed'): ?>
                                                    <i class="fas fa-times"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-user-clock"></i>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="log-content">
                                                <div class="log-user">
                                                    <?php echo htmlspecialchars($log['username'] ?? 'Unknown User'); ?>
                                                    <span class="<?php echo $statusBadge; ?> status-badge">
                                                        <?php echo $statusText; ?>
                                                    </span>
                                                </div>
                                                <div class="log-action">
                                                    <?php echo htmlspecialchars($log['aksi']); ?>
                                                </div>
                                                <div class="log-details">
                                                    <?php if (!empty($log['tipe_data'])): ?>
                                                        <span class="me-3">
                                                            <i class="fas fa-database me-1"></i>
                                                            <?php echo htmlspecialchars($log['tipe_data']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($log['deskripsi'])): ?>
                                                        <span>
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            <?php echo htmlspecialchars(substr($log['deskripsi'], 0, 100)); ?>
                                                            <?php if (strlen($log['deskripsi']) > 100): ?>...<?php endif; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <?php if (!empty($log['ip_address'])): ?>
                                                    <div class="log-ip">
                                                        <i class="fas fa-network-wired me-1"></i>
                                                        <?php echo htmlspecialchars($log['ip_address']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="log-time">
                                                <div class="fw-bold"><?php echo date('H:i', strtotime($log['created_at'])); ?></div>
                                                <div class="text-muted small">
                                                    <?php echo date('d M Y', strtotime($log['created_at'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="no-data">
                                        <i class="fas fa-history fa-4x mb-3"></i>
                                        <h5>Belum Ada Log Aktivitas</h5>
                                        <p>Tidak ada aktivitas login yang tercatat</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/script.php'; ?>
<?php include '../../partials/footer.php'; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Chart
    var chartOptions = {
        series: [
            {
                name: 'Total Login',
                data: <?php echo json_encode($chartDataTotal); ?>
            },
            {
                name: 'Berhasil',
                data: <?php echo json_encode($chartDataSuccess); ?>
            },
            {
                name: 'Gagal',
                data: <?php echo json_encode($chartDataFailed); ?>
            }
        ],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                }
            }
        },
        colors: ['#667eea', '#28a745', '#dc3545'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 5,
                borderRadiusApplication: 'end',
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: <?php echo json_encode($chartLabels); ?>,
            labels: {
                style: {
                    colors: '#718096',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Jumlah Login',
                style: {
                    color: '#718096',
                    fontSize: '14px'
                }
            },
            labels: {
                style: {
                    colors: '#718096',
                    fontSize: '12px'
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " login"
                }
            },
            theme: 'light'
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '14px',
            itemMargin: {
                horizontal: 10,
                vertical: 5
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#loginChart"), chartOptions);
    chart.render();

    // Filter functionality
    $('#filterUser, #filterStatus, #filterDate').on('change', function() {
        filterLogs();
    });

    // Search functionality
    $('#searchLogs').on('keyup', function() {
        filterLogs();
    });

    function filterLogs() {
        var userId = $('#filterUser').val();
        var status = $('#filterStatus').val();
        var date = $('#filterDate').val();
        var searchText = $('#searchLogs').val().toLowerCase();
        
        $('.log-item').each(function() {
            var show = true;
            var itemUserId = $(this).data('user-id');
            var itemStatus = $(this).data('status');
            var itemDate = $(this).data('date');
            var itemSearch = $(this).data('search');
            
            if (userId && itemUserId != userId) {
                show = false;
            }
            
            if (status && itemStatus != status) {
                show = false;
            }
            
            if (date && itemDate != date) {
                show = false;
            }
            
            if (searchText && itemSearch.indexOf(searchText) === -1) {
                show = false;
            }
            
            if (show) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // Update counter
        var visibleCount = $('.log-item:visible').length;
        $('.card-header-custom h5').text('Aktivitas Login Terbaru (' + visibleCount + ' log)');
    }

    function resetFilters() {
        $('#filterUser').val('');
        $('#filterStatus').val('');
        $('#filterDate').val('<?php echo date("Y-m-d"); ?>');
        $('#searchLogs').val('');
        $('.log-item').show();
        $('.card-header-custom h5').text('Aktivitas Login Terbaru (<?php echo $totalLogs; ?> log)');
    }

    // Auto-refresh every 60 seconds
    setInterval(function() {
        $.ajax({
            url: 'get_new_logs.php', // Buat file ini untuk mendapatkan log baru saja
            type: 'GET',
            success: function(response) {
                // Implement jika perlu auto-update
                console.log('Checking for new logs...');
            }
        });
    }, 60000);

    // Export to CSV
    $('#exportCSV').on('click', function() {
        var csv = [];
        var headers = ['Timestamp', 'Username', 'Role', 'Action', 'Status', 'IP Address', 'Description'];
        csv.push(headers.join(','));
        
        $('.log-item').each(function() {
            var row = [];
            var time = $(this).find('.log-time div:first').text();
            var date = $(this).find('.log-time .small').text();
            var username = $(this).find('.log-user').text().split(' ')[0];
            var status = $(this).find('.status-badge').text();
            var action = $(this).find('.log-action').text();
            var ip = $(this).find('.log-ip').text().replace('IP: ', '');
            var desc = $(this).find('.log-details').text();
            
            row.push('"' + date + ' ' + time + '"');
            row.push('"' + username + '"');
            row.push('"' + status + '"');
            row.push('"' + action + '"');
            row.push('"' + status + '"');
            row.push('"' + ip + '"');
            row.push('"' + desc + '"');
            
            csv.push(row.join(','));
        });
        
        var csvString = csv.join('\n');
        var blob = new Blob([csvString], { type: 'text/csv' });
        var url = window.URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'login_logs_' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });

    // Initialize today's date
    $('#filterDate').val('<?php echo date("Y-m-d"); ?>');
});
</script>

</body>
</html>