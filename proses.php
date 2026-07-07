<?php
// proses.php
require_once 'config/config.php';

// A. AMBIL DATA KRITERIA
$kriteria_res = $conn->query("SELECT * FROM kriteria ORDER BY kode_kriteria");
$kriteria = []; $bobot = []; $atribut = []; $list_kriteria = [];
while ($row = $kriteria_res->fetch_assoc()) {
    $id_k = $row['id_kriteria'];
    $kriteria[$id_k] = $row['kode_kriteria'];
    $bobot[$id_k] = $row['bobot'];
    $atribut[$id_k] = $row['atribut'];
    $list_kriteria[] = $row;
}

// B. AMBIL DATA ALTERNATIF
$alternatif_res = $conn->query("SELECT * FROM alternatif");
$alternatif = [];
while ($row = $alternatif_res->fetch_assoc()) {
    $alternatif[$row['id_alternatif']] = $row['nama_alternatif'];
}

// C. AMBIL MATRIKS KEPUTUSAN MENTAH (X)
$matriks_res = $conn->query("SELECT * FROM matriks_keputusan");
$matriks_x = [];
while ($row = $matriks_res->fetch_assoc()) {
    $matriks_x[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai'];
}

// D. HITUNG LOGIKA MATEMATIKA MOORA
$penyebut = [];
$matriks_normalisasi = [];
$matriks_optimasi_bobot = [];
$tabel_optimasi_akhir = [];

if (!empty($alternatif) && !empty($kriteria)) {
    // 1. Cari Nilai Penyebut per Kriteria
    foreach ($kriteria as $id_k => $kode) {
        $jumlah_kuadrat = 0;
        foreach ($alternatif as $id_alt => $nama) {
            $nilai = isset($matriks_x[$id_alt][$id_k]) ? $matriks_x[$id_alt][$id_k] : 0;
            $jumlah_kuadrat += pow($nilai, 2);
        }
        $penyebut[$id_k] = sqrt($jumlah_kuadrat);
    }

    // 2. Hitung Normalisasi Tradisional & Normalisasi Berbobot
    foreach ($alternatif as $id_alt => $nama) {
        foreach ($kriteria as $id_k => $kode) {
            $nilai_mentah = isset($matriks_x[$id_alt][$id_k]) ? $matriks_x[$id_alt][$id_k] : 0;
            
            // Matriks Normalisasi (X')
            $norm = ($penyebut[$id_k] > 0) ? ($nilai_mentah / $penyebut[$id_k]) : 0;
            $matriks_normalisasi[$id_alt][$id_k] = $norm;
            
            // Matriks Normalisasi Berbobot
            $matriks_optimasi_bobot[$id_alt][$id_k] = $norm * $bobot[$id_k];
        }
    }

    // 3. Hitung Nilai Optimasi Akhir (Yi = Max - Min)
    foreach ($alternatif as $id_alt => $nama) {
        $max = 0; $min = 0;
        foreach ($kriteria as $id_k => $kode) {
            $nilai_berbobot = $matriks_optimasi_bobot[$id_alt][$id_k];
            if ($atribut[$id_k] == 'benefit') {
                $max += $nilai_berbobot;
            } else {
                $min += $nilai_berbobot;
            }
        }
        $tabel_optimasi_akhir[] = [
            'nama_alternatif' => $nama,
            'max' => $max,
            'min' => $min,
            'yi'  => $max - $min
        ];
    }
}

// Lempar seluruh hasil array hitungan ke file tampilan
include 'views/moora_proses.php';
?>