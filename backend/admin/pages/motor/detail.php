<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'motor';
include '../../partials/sidebar.php';
include '../../app.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];

// Query detail motor
$qDetail = "SELECT b.*, k.nama_kategori 
            FROM barang b
            LEFT JOIN kategori k ON b.kategori_id = k.id
            WHERE b.id = $id";
$result = mysqli_query($connect, $qDetail);
$motor = mysqli_fetch_object($result);

if (!$motor) {
    header("Location: index.php");
    exit;
}
?>

<style>
/* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #f0f2f5;
    overflow-x: hidden;
}

/* MAIN CONTENT - Tidak Nabrak Sidebar */
#main {
    margin-left: 260px;
    margin-top: 70px;
    padding: 25px;
    width: calc(100% - 260px);
    min-height: calc(100vh - 70px);
    transition: all 0.3s ease;
    background: #f0f2f5;
    position: relative;
    z-index: 1;
}

/* Modern Detail Styles */
.detail-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    overflow: hidden;
}

.detail-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 30px;
    text-align: center;
}

.detail-header .motor-icon {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.detail-header .motor-icon i {
    font-size: 40px;
    color: white;
}

.detail-header h2 {
    color: white;
    margin: 0 0 10px 0;
    font-size: 1.8rem;
    font-weight: 700;
}

.detail-header .kode-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    padding: 5px 15px;
    border-radius: 20px;
    color: white;
    font-size: 0.9rem;
}

.detail-body {
    padding: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.info-item {
    background: #f8fafc;
    border-radius: 16px;
    padding: 20px;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.info-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

.info-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    color: #667eea;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
}

.status-badge-large {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 600;
}
.status-tersedia { background: #d4edda; color: #155724; }
.status-dipinjam { background: #fff3cd; color: #856404; }
.status-rusak { background: #f8d7da; color: #721c24; }
.status-hilang { background: #d1ecf1; color: #0c5460; }

.price-highlight {
    font-size: 1.4rem;
    font-weight: 800;
    color: #48bb78;
}

.deskripsi-section {
    background: #f8fafc;
    border-radius: 16px;
    padding: 20px;
    margin-top: 20px;
    border: 1px solid #e2e8f0;
}

.deskripsi-text {
    color: #4a5568;
    line-height: 1.6;
    font-size: 0.95rem;
}

.btn-group-action {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f2f5;
}

.btn-back {
    background: #f8f9fa;
    border: 2px solid #e2e8f0;
    padding: 10px 25px;
    border-radius: 10px;
    font-weight: 600;
    color: #4a5568;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
    color: #4a5568;
    text-decoration: none;
}

.btn-edit-detail {
    background: linear-gradient(135deg, #fad961 0%, #f76b1c 100%);
    border: none;
    padding: 10px 30px;
    border-radius: 10px;
    font-weight: 600;
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-edit-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(247,107,28,0.4);
    color: white;
    text-decoration: none;
}

.btn-delete-detail {
    background: linear-gradient(135deg, #ff5858 0%, #f09819 100%);
    border: none;
    padding: 10px 30px;
    border-radius: 10px;
    font-weight: 600;
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-delete-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255,88,88,0.4);
    color: white;
    text-decoration: none;
}

/* Responsive */
@media (max-width: 992px) {
    #main {
        margin-left: 0;
        width: 100%;
        padding: 15px;
    }
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    .detail-body {
        padding: 20px;
    }
    .detail-header h2 {
        font-size: 1.4rem;
    }
    .btn-group-action {
        flex-direction: column;
    }
    .btn-back, .btn-edit-detail, .btn-delete-detail {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div id="main">
    <div class="detail-card">
        <div class="detail-header">
            <div class="motor-icon">
                <i class="fas fa-motorcycle"></i>
            </div>
            <h2><?= htmlspecialchars($motor->nama_barang) ?></h2>
            <div class="kode-badge">
                <i class="fas fa-barcode me-1"></i> <?= htmlspecialchars($motor->kode_barang) ?>
            </div>
        </div>
        
        <div class="detail-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-folder"></i> Kategori</div>
                    <div class="info-value"><?= htmlspecialchars($motor->nama_kategori ?? '-') ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-trademark"></i> Merk</div>
                    <div class="info-value"><?= htmlspecialchars($motor->merk ?? '-') ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-calendar"></i> Tahun</div>
                    <div class="info-value"><?= $motor->tahun ?? '-' ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-boxes"></i> Total Stok</div>
                    <div class="info-value"><span class="badge bg-info"><?= $motor->stok ?> Unit</span></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-check-circle"></i> Tersedia</div>
                    <div class="info-value"><span class="badge bg-success"><?= $motor->jumlah_tersedia ?> Unit</span></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-chart-line"></i> Status</div>
                    <div class="info-value">
                        <?php
                        $statusClass = '';
                        $statusIcon = '';
                        switch($motor->status) {
                            case 'tersedia': $statusClass = 'status-tersedia'; $statusIcon = 'fa-check-circle'; $statusText = 'Tersedia'; break;
                            case 'dipinjam': $statusClass = 'status-dipinjam'; $statusIcon = 'fa-clock'; $statusText = 'Dipinjam'; break;
                            case 'rusak': $statusClass = 'status-rusak'; $statusIcon = 'fa-tools'; $statusText = 'Rusak'; break;
                            case 'hilang': $statusClass = 'status-hilang'; $statusIcon = 'fa-question-circle'; $statusText = 'Hilang'; break;
                            default: $statusClass = 'status-tersedia'; $statusIcon = 'fa-check-circle'; $statusText = 'Tersedia';
                        }
                        ?>
                        <span class="status-badge-large <?= $statusClass ?>">
                            <i class="fas <?= $statusIcon ?>"></i> <?= $statusText ?>
                        </span>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-money-bill"></i> Harga Sewa per Hari</div>
                    <div class="info-value price-highlight">Rp <?= number_format($motor->harga_sewa_perhari, 0, ',', '.') ?></div>
                </div>
            </div>
            
            <?php if (!empty($motor->deskripsi)): ?>
            <div class="deskripsi-section">
                <div class="info-label"><i class="fas fa-align-left"></i> Deskripsi</div>
                <div class="deskripsi-text"><?= nl2br(htmlspecialchars($motor->deskripsi)) ?></div>
            </div>
            <?php endif; ?>
            
            <div class="btn-group-action">
                <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
                <a href="edit.php?id=<?= $motor->id ?>" class="btn-edit-detail"><i class="fas fa-edit"></i> Edit</a>
                <a href="../../action/motor/destroy.php?id=<?= $motor->id ?>" 
                   onclick="return confirm('Hapus motor <?= addslashes($motor->nama_barang) ?>?')"
                   class="btn-delete-detail"><i class="fas fa-trash"></i> Hapus</a>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; ?>
<?php include '../../partials/script.php'; ?>