<?php
// form_alternatif.php

// 1. HUBUNGKAN KE KONFIGURASI DATABASE
require_once 'config/config.php';

$id_alt = ""; 
$nama_alt = ""; 
$is_edit = false; 
$nilai_lama = [];

// 2. JIKA MODE EDIT: MUAT DATA LAMA DARI DATABASE
if (isset($_GET['edit'])) {
    $id_alt = intval($_GET['edit']);
    $is_edit = true;
    
    // Ambil nama alternatif
    $alt_res = $conn->query("SELECT nama_alternatif FROM alternatif WHERE id_alternatif = $id_alt");
    if ($alt_data = $alt_res->fetch_assoc()) { 
        $nama_alt = $alt_data['nama_alternatif']; 
    }
    
    // Ambil nilai kriteria yang sudah ada sebelumnya
    $matriks_res = $conn->query("SELECT id_kriteria, nilai FROM matriks_keputusan WHERE id_alternatif = $id_alt");
    while ($m = $matriks_res->fetch_assoc()) { 
        $nilai_lama[$m['id_kriteria']] = $m['nilai']; 
    }
}

// 3. AMBIL DATA KRITERIA (Untuk membuat input form dinamis di bagian View)
$kriteria_res = $conn->query("SELECT * FROM kriteria ORDER BY kode_kriteria");
$list_kriteria = [];
while ($k = $kriteria_res->fetch_assoc()) { 
    $list_kriteria[] = $k; 
}


// 4. PROSES SIMPAN DATA (Ketik Tombol Simpan Diklik)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mencegah sql injection dasar dengan real_escape_string
    $nama_pupuk = $conn->real_escape_string($_POST['nama_alternatif']);
    $nilai_input = $_POST['nilai_kriteria']; // Array berpasangan [id_kriteria => nilai]

    if ($is_edit) {
        // A. PROSES UPDATE DATA
        $conn->query("UPDATE alternatif SET nama_alternatif = '$nama_pupuk' WHERE id_alternatif = $id_alt");
        
        // Perbarui nilai kriteria satu per satu di tabel matriks
        foreach ($nilai_input as $id_k => $nilai) {
            $nilai = doubleval($nilai);
            $conn->query("UPDATE matriks_keputusan SET nilai = '$nilai' WHERE id_alternatif = $id_alt AND id_kriteria = $id_k");
        }
    } else {
        // B. PROSES SIMPAN DATA BARU (CREATE)
        $conn->query("INSERT INTO alternatif (nama_alternatif) VALUES ('$nama_pupuk')");
        $id_baru = $conn->insert_id; // Ambil ID alternatif yang baru saja tersimpan otomatis
        
        // Masukkan nilai parameter ke tabel matriks keputusan
        foreach ($list_kriteria as $k) {
            $id_k = $k['id_kriteria'];
            $nilai = isset($nilai_input[$id_k]) ? doubleval($nilai_input[$id_k]) : 0;
            $conn->query("INSERT INTO matriks_keputusan (id_alternatif, id_kriteria, nilai) VALUES ('$id_baru', '$id_k', '$nilai')");
        }
    }
    
    // Alihkan kembali ke halaman utama manajemen data alternatif
    header("Location: alternatif.php");
    exit();
}

// 5. LEMPAR DATA KE FILE VIEW UNTUK MENAMPILKAN FORM
include 'views/alternatif_form.php';
?>