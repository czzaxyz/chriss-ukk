<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'motor';
include '../../partials/sidebar.php';
include '../../app.php';

// Ambil data kategori untuk dropdown
$qKategori = "SELECT * FROM kategori ORDER BY nama_kategori";
$resultKategori = mysqli_query($connect, $qKategori);
$kategori = [];
while ($row = mysqli_fetch_assoc($resultKategori)) {
    $kategori[] = $row;
}
?>

<style>
/* Reset dan Base */
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

/* Modern Form Styles */
.create-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    overflow: hidden;
    max-width: 100%;
}

.create-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px 30px;
}

.create-header h4 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: white;
}

.create-header h4 i {
    margin-right: 10px;
}

.create-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label-modern {
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #4a5568;
    display: block;
    letter-spacing: 0.5px;
}

.form-label-modern i {
    margin-right: 8px;
    color: #667eea;
    width: 20px;
}

.form-control-modern, .form-select-modern {
    width: 100%;
    padding: 12px 15px;
    font-size: 0.9rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.form-control-modern:focus, .form-select-modern:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    outline: none;
}

.form-control-modern:hover, .form-select-modern:hover {
    border-color: #cbd5e0;
}

.input-group-modern {
    display: flex;
    align-items: stretch;
}

.input-group-text-modern {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-right: none;
    padding: 0 15px;
    border-radius: 12px 0 0 12px;
    color: #4a5568;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.input-group-modern .form-control-modern {
    border-radius: 0 12px 12px 0;
}

textarea.form-control-modern {
    resize: vertical;
    min-height: 100px;
}

.btn-group-action {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f2f5;
}

.btn-cancel {
    background: #f8f9fa;
    border: 2px solid #e2e8f0;
    padding: 10px 25px;
    border-radius: 10px;
    font-weight: 600;
    color: #4a5568;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-cancel:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
    color: #4a5568;
    text-decoration: none;
}

.btn-create {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 10px 30px;
    border-radius: 10px;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102,126,234,0.3);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102,126,234,0.4);
    color: white;
}

.alert-modern {
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 25px;
    border: none;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-modern.alert-danger {
    background: #fff5f5;
    color: #c53030;
    border-left: 4px solid #fc8181;
}

.alert-modern.alert-success {
    background: #f0fff4;
    color: #276749;
    border-left: 4px solid #68d391;
}

.row-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
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
    .row-grid {
        grid-template-columns: 1fr;
    }
    .create-body {
        padding: 20px;
    }
    .btn-group-action {
        flex-direction: column;
    }
    .btn-cancel, .btn-create {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div id="main">
    <div class="create-card">
        <div class="create-header">
            <h4>
                <i class="fas fa-plus-circle"></i>
                Tambah Data Motor
            </h4>
        </div>
        <div class="create-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert-modern alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert-modern alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <form action="../../action/motor/store.php" method="POST">
                <div class="row-grid">
                    <div class="form-group">
                        <label class="form-label-modern">
                            <i class="fas fa-barcode"></i> Kode Motor *
                        </label>
                        <input type="text" name="kode_barang" class="form-control-modern" 
                               placeholder="MTR-001" required>
                        <small style="font-size: 0.7rem; color: #718096; margin-top: 5px; display: block;">
                            Kode harus unik, contoh: MTR-001
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-modern">
                            <i class="fas fa-motorcycle"></i> Nama Motor *
                        </label>
                        <input type="text" name="nama_barang" class="form-control-modern" 
                               placeholder="Honda Vario 150" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-modern">
                            <i class="fas fa-folder"></i> Kategori *
                        </label>
                        <select name="kategori_id" class="form-select-modern" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($kategori as $kat): ?>
                                <option value="<?= $kat['id'] ?>">
                                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-modern">
                            <i class="fas fa-trademark"></i> Merk
                        </label>
                        <input type="text" name="merk" class="form-control-modern" 
                               placeholder="Honda / Yamaha / Suzuki">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-modern">
                            <i class="fas fa-calendar"></i> Tahun
                        </label>
                        <input type="number" name="tahun" class="form-control-modern" 
                               placeholder="2024" min="2000" max="<?= date('Y') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-modern">
                            <i class="fas fa-boxes"></i> Stok *
                        </label>
                        <input type="number" name="stok" class="form-control-modern" 
                               value="1" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-modern">
                            <i class="fas fa-chart-line"></i> Status *
                        </label>
                        <select name="status" class="form-select-modern" required>
                            <option value="tersedia" selected>Tersedia</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="rusak">Rusak</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label-modern">
                            <i class="fas fa-money-bill"></i> Harga Sewa per Hari *
                        </label>
                        <div class="input-group-modern">
                            <span class="input-group-text-modern">Rp</span>
                            <input type="number" name="harga_sewa_perhari" class="form-control-modern" 
                                   placeholder="80000" required min="0" step="1000">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label-modern">
                        <i class="fas fa-align-left"></i> Deskripsi
                    </label>
                    <textarea name="deskripsi" class="form-control-modern" rows="4" 
                              placeholder="Deskripsi singkat tentang motor..."></textarea>
                </div>
                
                <div class="btn-group-action">
                    <a href="index.php" class="btn-cancel">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="tombol" class="btn-create">
                        <i class="fas fa-save"></i> Simpan Motor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; ?>
<?php include '../../partials/script.php'; ?>