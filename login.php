<?php
// login.php
require_once 'config/config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Cari user di database
    $query = $conn->query("SELECT * FROM users WHERE username = '$username'");
    
    if ($query->num_rows > 0) {
        $user = $query->fetch_assoc();
        
        // Verifikasi password (jika di db memakai md5/hash, sesuaikan bagian ini)
        // Di sini kita gunakan teks biasa sesuai seeder data sebelumnya demi kemudahan uji coba
        if ($password === $user['password']) {
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Menyimpan role 'admin' ke session
            $_SESSION['nama'] = $user['nama_lengkap'];
            
            header("Location: index.php");
            exit();
        } else {
            $error = 'Password yang Anda masukkan salah!';
        }
    } else {
        $error = 'Username tidak terdaftar!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Admin - UD. APOLLO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f8f9fa;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        width: 100%;
        max-width: 400px;
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }
    </style>
</head>

<body>
    <div class="login-card">
        <h4 class="fw-bold text-dark text-center mb-1">🔐 Login Admin</h4>
        <p class="text-secondary text-center small mb-4">UD. APOLLO Kecamatan Kutalimbaru</p>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger border-0 small py-2"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Username</label>
                <input type="text" name="username" class="form-control bg-light border-0" required
                    placeholder="Masukkan username">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold text-secondary">Password</label>
                <input type="password" name="password" class="form-control bg-light border-0" required
                    placeholder="Masukkan password">
            </div>
            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold rounded-3">Masuk Sistem</button>
            <a href="index.php" class="btn btn-light w-100 py-2 mt-2 border text-secondary small rounded-3">Kembali ke
                Dashboard</a>
        </form>
    </div>
</body>

</html>