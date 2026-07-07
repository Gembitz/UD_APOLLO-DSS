<!-- includes/sidebar.php -->
<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Fungsi cek admin lokal agar tidak bentrok dengan berkas lain
$is_admin_logged = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>
<div class="bg-dark text-white p-3 vh-100 position-sticky top-0 d-flex flex-column justify-content-between"
    style="width: 260px;">
    <div>
        <div class="d-flex align-items-center mb-4 pb-2 border-bottom border-secondary">
            <span class="fs-4 fw-bold text-success">🌱 UD. APOLLO</span>
        </div>
        <ul class="nav nav-pills flex-column mb-auto gap-1">
            <li class="nav-item">
                <a href="index.php" class="nav-link text-white d-flex align-items-center gap-2" id="nav-dashboard">
                    📊 Dashboard Hasil
                </a>
            </li>
            <li>
                <a href="alternatif.php" class="nav-link text-white d-flex align-items-center gap-2"
                    id="nav-alternatif">
                    📦 Kelola Alternatif
                </a>
            </li>
            <li>
                <a href="proses.php" class="nav-link text-white d-flex align-items-center gap-2" id="nav-proses">
                    🧮 Proses MOORA
                </a>
            </li>
            <li>
                <a href="banding.php" class="nav-link text-white d-flex align-items-center gap-2" id="nav-banding">
                    ⚖️ Bandingkan Pupuk
                </a>
            </li>
        </ul>
    </div>

    <!-- BAGIAN FOOTER SIDEBAR INTERAKTIF HAK AKSES -->
    <div class="pt-3 border-top border-secondary">
        <?php if ($is_admin_logged): ?>
        <div class="small text-secondary mb-2">Login sebagai:</div>
        <div class="fw-bold text-success text-truncate mb-2" title="<?= $_SESSION['nama']; ?>">
            👨‍💼 <?= $_SESSION['nama']; ?>
        </div>
        <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 py-1 rounded-2 fw-semibold">Keluar Sistem</a>
        <?php else: ?>
        <div class="text-center">
            <a href="login.php" class="text-secondary text-decoration-none small opacity-50 hover-opacity-100">🔐 Login
                Area Admin</a>
        </div>
        <?php endif; ?>
    </div>
</div>