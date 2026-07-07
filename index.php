<?php
// index.php

// 1. HUBUNGKAN KE KONFIGURASI DATABASE (Sesuaikan Jalur Folder)
require_once 'config/config.php';

// 2. AMBIL DATA KRITERIA DAN BOBOT
$kriteria_res = $conn->query("SELECT * FROM kriteria");
$kriteria = []; 
$bobot = []; 
$atribut = [];

while ($row = $kriteria_res->fetch_assoc()) {
    $id_k = $row['id_kriteria'];
    $kriteria[$id_k] = $row['kode_kriteria'];
    $bobot[$id_k] = $row['bobot'];
    $atribut[$id_k] = $row['atribut'];
}

// 3. AMBIL DATA ALTERNATIF
$alternatif_res = $conn->query("SELECT * FROM alternatif");
$alternatif = [];
while ($row = $alternatif_res->fetch_assoc()) {
    $alternatif[$row['id_alternatif']] = $row['nama_alternatif'];
}

// 4. AMBIL DATA MATRIKS KEPUTUSAN MENTAH (X)
$matriks_res = $conn->query("SELECT * FROM matriks_keputusan");
$matriks_x = [];
while ($row = $matriks_res->fetch_assoc()) {
    $matriks_x[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai'];
}

// 5. PROSES PERHITUNGAN LOGIKA METODE MOORA
$hasil_moora = [];

// Proteksi sistem: Jalankan rumus hanya jika kriteria dan alternatif sudah diisi di database
if (!empty($alternatif) && !empty($kriteria)) {
    
    // TAHAP A: Menghitung nilai penyebut (akar dari jumlah kuadrat per kriteria)
    $penyebut = [];
    foreach ($kriteria as $id_k => $kode) {
        $jumlah_kuadrat = 0;
        foreach ($alternatif as $id_alt => $nama) {
            $nilai = isset($matriks_x[$id_alt][$id_k]) ? $matriks_x[$id_alt][$id_k] : 0;
            $jumlah_kuadrat += pow($nilai, 2);
        }
        $penyebut[$id_k] = sqrt($jumlah_kuadrat);
    }

    // TAHAP B: Normalisasi Matriks, Multiplikasi Bobot, dan Pengurangan Max-Min (Yi)
    foreach ($alternatif as $id_alt => $nama) {
        $max = 0; // Menampung total nilai kriteria bertipe 'benefit'
        $min = 0; // Menampung total nilai kriteria bertipe 'cost'
        
        foreach ($kriteria as $id_k => $kode) {
            $nilai_mentah = isset($matriks_x[$id_alt][$id_k]) ? $matriks_x[$id_alt][$id_k] : 0;
            
            // Lakukan normalisasi jika nilai penyebut lebih dari nol (menghindari pembagian dengan nol)
            $nilai_normalisasi_bobot = ($penyebut[$id_k] > 0) ? ($nilai_mentah / $penyebut[$id_k]) * $bobot[$id_k] : 0;
            
            if ($atribut[$id_k] == 'benefit') {
                $max += $nilai_normalisasi_bobot;
            } else {
                $min += $nilai_normalisasi_bobot;
            }
        }
        
        // Simpan hasil kalkulasi akhir ke dalam array pendukung keputusan
        $hasil_moora[] = [
            'nama_alternatif' => $nama,
            'max' => $max,
            'min' => $min,
            'yi'  => $max - $min
        ];
    }
    
    // TAHAP C: Perangkingan (Mengurutkan dari nilai Yi terbesar ke terkecil)
    usort($hasil_moora, function($a, $b) { 
        return $b['yi'] <=> $a['yi']; 
    });
}

// 6. LEMPAR HASIL DATA KE FILE VIEW UNTUK DITAMPILKAN KE PENGGUNA
include 'views/dashboard.php';
?>