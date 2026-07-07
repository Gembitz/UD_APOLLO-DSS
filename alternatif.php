<?php
// alternatif.php

// 1. HUBUNGKAN KE KONFIGURASI DATABASE
require_once 'config/config.php';

// 2. PROSES HAPUS DATA (DELETE)
if (isset($_GET['hapus'])) {
    // Menggunakan intval untuk keamanan guna mencegah SQL Injection
    $id_alt = intval($_GET['hapus']);
    
    // Karena kita sudah menyetel ON DELETE CASCADE pada foreign key database,
    // data nilai di tabel 'matriks_keputusan' yang terhubung dengan id ini akan otomatis ikut terhapus.
    $conn->query("DELETE FROM alternatif WHERE id_alternatif = $id_alt");
    
    // Redirect kembali ke halaman alternatif agar URL bersih dari parameter ?hapus=ID
    header("Location: alternatif.php");
    exit();
}

// 3. AMBIL DATA KRITERIA (Untuk kebutuhan Header Tabel di bagian View)
$kriteria_res = $conn->query("SELECT * FROM kriteria ORDER BY kode_kriteria");
$list_kriteria = [];
while ($k = $kriteria_res->fetch_assoc()) {
    $list_kriteria[] = $k;
}

// 4. AMBIL DATA ALTERNATIF (Untuk isi baris tabel di bagian View)
$alternatif_res = $conn->query("SELECT * FROM alternatif");

// 5. LEMPAR DATA KE FILE VIEW UNTUK DITAMPILKAN
include 'views/alternatif_list.php';
?>