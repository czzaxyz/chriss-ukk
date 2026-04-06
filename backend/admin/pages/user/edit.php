<?php
// AKTIFKAN ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan session_start() di awal
session_start();

include '../../partials/header.php'; 
$page = 'pengguna'; 
include '../../partials/sidebar.php'; 

// Include action untuk mengambil data
include '../../action/user/show.php';
?>

<style>
    /* Reset Background Colors */
    body, #main, .container-fluid, .page-body-wrapper {
        background-color: #f8f9fc !important;
    }
    
    #main {
        margin-left: 260px;
        margin-top: 70px;
        padding: 20px;
        width: calc(100% - 260px);
        box-sizing: border-box;
        background-color: #f8f9fc !important;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        border: 1px solid #e3e6f0 !important;
        background-color: #ffffff !important;
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e3e6f0 !important;
        padding: 20px 25px !important;
        border-radius: 12px 12px 0 0 !important;
    }

    .card-body {
        padding: 25px !important;
        background-color: #ffffff !important;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #4e73df;
        margin-bottom: 0;
    }

    .btn-primary {
        background-color: #4e73df !important;
        border-color: #4e73df !important;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-success {
        background-color: #1cc88a !important;
        border-color: #1cc88a !important;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover, .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .form-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 8px;
    }

    .form-control, .form-select, .form-textarea {
        border: 1px solid #d1d3e2 !important;
        border-radius: 0.35rem !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.9rem !important;
        background-color: #ffffff !important;
        color: #000000 !important;
    }

    .form-control:focus, .form-select:focus, .form-textarea:focus {
        border-color: #bac8f3 !important;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25) !important;
    }

    .form-text {
        color: #858796 !important;
        font-size: 0.85rem;
        margin-top: 5px;
    }

    .alert {
        border-radius: 8px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background-color: #f8d7da !important;
        color: #721c24 !important;
        border-left: 4px solid #e74a3b !important;
    }

    .alert-success {
        background-color: #d4edda !important;
        color: #155724 !important;
        border-left: 4px solid #1cc88a !important;
    }

    .header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f2f5;
    }

    @media (max-width: 768px) {
        #main {
            margin-left: 0;
            width: 100%;
            padding: 15px;
        }
        
        .header-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container-fluid page-body-wrapper">
    <div id="main" class="p-4">
        <div class="card">
            <div class="card-header">
                <div class="header-container">
                    <h4 class="fw-bold text-primary page-title">
                        <i class="fas fa-user-edit"></i> Edit Data User
                    </h4>
                    <a href="./index.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Tampilkan pesan error/success dari session -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <form action="../../action/user/update.php?id=<?= $user->id ?>" method="POST" class="needs-validation" novalidate>
                            <!-- ID User (readonly) -->
                            <div class="mb-4">
                                <label for="id" class="form-label">ID User</label>
                                <input type="text" class="form-control" id="id_display"
                                    value="#<?= htmlspecialchars($user->id ?? '') ?>" readonly disabled
                                    style="background-color: #f8f9fa; color: #6c757d;">
                                <input type="hidden" name="id" value="<?= $user->id ?>">
                                <div class="form-text">ID User tidak dapat diubah</div>
                            </div>
                            
                            <!-- Username -->
                            <div class="mb-4">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" name="username" class="form-control" id="username"
                                    placeholder="Masukkan username.." required 
                                    value="<?= htmlspecialchars($user->username ?? '') ?>">
                                <div class="invalid-feedback">Harap isi username</div>
                            </div>
                            
                            <!-- Nama Lengkap -->
                            <div class="mb-4">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap *</label>
                                <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap"
                                    placeholder="Masukkan nama lengkap.." required
                                    value="<?= htmlspecialchars($user->nama_lengkap ?? '') ?>">
                                <div class="invalid-feedback">Harap isi nama lengkap</div>
                            </div>
                            
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    placeholder="Masukkan email.."
                                    value="<?= htmlspecialchars($user->email ?? '') ?>">
                                <div class="form-text">Contoh: nama@contoh.com</div>
                            </div>
                            
                            <!-- No Telepon -->
                            <div class="mb-4">
                                <label for="no_telp" class="form-label">No. Telepon</label>
                                <input type="tel" name="no_telp" class="form-control" id="no_telp"
                                    placeholder="Masukkan nomor telepon.."
                                    value="<?= htmlspecialchars($user->no_telp ?? '') ?>">
                                <div class="form-text">Contoh: 081234567890</div>
                            </div>
                            
                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="Masukkan password baru..">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                            </div>
                            
                            <!-- Alamat -->
                            <div class="mb-4">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control form-textarea" id="alamat"
                                    placeholder="Masukkan alamat.." rows="3"><?= htmlspecialchars($user->alamat ?? '') ?></textarea>
                            </div>
                            
                            <!-- Role -->
                            <div class="mb-4">
                                <label for="role" class="form-label">Role *</label>
                                <select name="role" class="form-select" id="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin" <?php echo ($user->role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="petugas" <?php echo ($user->role == 'petugas') ? 'selected' : ''; ?>>Petugas</option>
                                    <option value="peminjam" <?php echo ($user->role == 'peminjam') ? 'selected' : ''; ?>>Peminjam</option>
                                </select>
                                <div class="invalid-feedback">Harap pilih role user</div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-5">
                                <a href="./index.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success" name="tombol">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../../partials/footer.php'; ?>
    </div>
</div>

<?php include '../../partials/script.php'; ?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
// Validasi form
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Toggle password visibility
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
        toggleButton.className = 'btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y';
        toggleButton.style.right = '10px';
        toggleButton.style.zIndex = '5';
        toggleButton.style.background = 'transparent';
        toggleButton.style.border = 'none';
        
        const inputGroup = passwordInput.parentElement;
        inputGroup.style.position = 'relative';
        inputGroup.appendChild(toggleButton);
        
        toggleButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    }
});
</script>