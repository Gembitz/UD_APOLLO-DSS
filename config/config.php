<?php
// config.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "spk_pupuk";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Fungsi helper untuk mempercantik prapeta text atribut
function statusBadge($text) {
    if (strtolower($text) == 'benefit') {
        return '<span class="badge bg-success-subtle text-success px-2 py-1">Benefit</span>';
    }
    return '<span class="badge bg-warning-subtle text-warning px-2 py-1">Cost</span>';
}
?>