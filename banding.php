<?php
// banding.php
require_once 'config/config.php';

// A. AMBIL SEMUA PILIHAN ALTERNATIF UNTUK DROPDOWN
$all_alt_res = $conn->query("SELECT * FROM alternatif");
$list_pilihan = [];
while ($row = $all_alt_res->fetch_assoc()) {
    $list_pilihan[] = $row;
}

// B. AMBIL KRITERIA UNTUK KEPERLUAN GRAFIK PARAMETER
$kriteria_res = $conn->query("SELECT * FROM kriteria ORDER BY kode_kriteria");
$kriteria = []; $bobot = []; $atribut = []; $list_kriteria = [];
while ($row = $kriteria_res->fetch_assoc()) {
    $id_k = $row['id_kriteria'];
    $kriteria[$id_k] = $row['kode_kriteria'];
    $bobot[$id_k] = $row['bobot'];
    $atribut[$id_k] = $row['atribut'];
    $list_kriteria[] = $row;
}

// C. LOGIKA HITUNG MOORA GLOBAL (Untuk mendapatkan nilai pembagi normalisasi yang valid)
$alternatif_res = $conn->query("SELECT * FROM alternatif");
$alternatif = [];
while ($row = $alternatif_res->fetch_assoc()) {
    $alternatif[$row['id_alternatif']] = $row['nama_alternatif'];
}

$matriks_res = $conn->query("SELECT * FROM matriks_keputusan");
$matriks_x = [];
while ($row = $matriks_res->fetch_assoc()) {
    $matriks_x[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai'];
}

$penyebut = [];
if (!empty($alternatif) && !empty($kriteria)) {
    foreach ($kriteria as $id_k => $kode) {
        $jumlah_kuadrat = 0;
        foreach ($alternatif as $id_alt => $nama) {
            $nilai = isset($matriks_x[$id_alt][$id_k]) ? $matriks_x[$id_alt][$id_k] : 0;
            $jumlah_kuadrat += pow($nilai, 2);
        }
        $penyebut[$id_k] = sqrt($jumlah_kuadrat);
    }
}

// D. PROSES PERBANDINGAN JIKA FORM DIKIRIM
$show_result = false;
$data_p1 = null; $data_p2 = null;
$pemenang = null;

if (isset($_GET['pupuk1']) && isset($_GET['pupuk2'])) {
    $id_p1 = intval($_GET['pupuk1']);
    $id_p2 = intval($_GET['pupuk2']);

    if ($id_p1 !== $id_p2 && isset($alternatif[$id_p1]) && isset($alternatif[$id_p2])) {
        $show_result = true;

        // Fungsi lokal untuk menghitung skor Yi individu objek
        function hitungSkorIndividu($id_alt, $nama, $kriteria, $matriks_x, $penyebut, $bobot, $atribut) {
            $max = 0; $min = 0; $nilai_kriteria_berbobot = [];
            foreach ($kriteria as $id_k => $kode) {
                $nilai_mentah = isset($matriks_x[$id_alt][$id_k]) ? $matriks_x[$id_alt][$id_k] : 0;
                $norm_bobot = ($penyebut[$id_k] > 0) ? ($nilai_mentah / $penyebut[$id_k]) * $bobot[$id_k] : 0;
                
                $nilai_kriteria_berbobot[$kode] = $norm_bobot;
                if ($atribut[$id_k] == 'benefit') {
                    $max += $norm_bobot;
                } else {
                    $min += $norm_bobot;
                }
            }
            return [
                'id' => $id_alt,
                'nama' => $nama,
                'chart_data' => array_values($nilai_kriteria_berbobot),
                'yi' => $max - $min
            ];
        }

        $data_p1 = hitungSkorIndividu($id_p1, $alternatif[$id_p1], $kriteria, $matriks_x, $penyebut, $bobot, $atribut);
        $data_p2 = hitungSkorIndividu($id_p2, $alternatif[$id_p2], $kriteria, $matriks_x, $penyebut, $bobot, $atribut);

        // Cari Pemenang berdasarkan Skor Akhir Yi Tertinggi
        if ($data_p1['yi'] > $data_p2['yi']) {
            $pemenang = $data_p1;
        } elseif ($data_p2['yi'] > $data_p1['yi']) {
            $pemenang = $data_p2;
        } else {
            $pemenang = 'seri';
        }
    }
}

// Lempar variabel ke file view
include 'views/moora_banding.php';
?>