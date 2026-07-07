<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard SPK Pupuk - UD. APOLLO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .card {
        border: none;
        border-radius: 12px;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
    }
    </style>
</head>

<body class="d-flex">

    <?php include 'includes/sidebar.php'; ?>

    <div class="flex-grow-1 p-5">
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark m-0">Dashboard Rekomendasi</h1>
            <p class="text-secondary">Hasil perangkingan kualitas produk pupuk buah menggunakan metode MOORA</p>
        </div>

        <?php if (empty($hasil_moora)): ?>
        <div class="alert alert-warning border-0 p-4 rounded-3 shadow-sm">
            <h5 class="fw-bold">Data Belum Lengkap!</h5>
            Silakan isi data alternatif dan matriks nilai terlebih dahulu di menu Kelola Alternatif.
        </div>
        <?php else: ?>
        <div class="card bg-success text-white p-4 mb-5 shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span class="text-uppercase small fw-bold opacity-75">Rekomendasi Utama</span>
                    <h2 class="fw-bold mt-1 mb-2">🏆 Pupuk <?= $hasil_moora[0]['nama_alternatif']; ?></h2>
                    <p class="m-0 opacity-75">Berdasarkan kalkulasi parameter jenis tanah, harga, kadar air, dan iklim,
                        produk ini memiliki indeks kepuasan tertinggi dengan skor akhir
                        <strong><?= number_format($hasil_moora[0]['yi'], 4); ?></strong>.</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Kolom Tabel Peringkat -->
            <div class="col-lg-7">
                <div class="table-container shadow-sm h-100">
                    <h4 class="fw-bold text-dark mb-4">Urutan Peringkat Seluruh Alternatif</h4>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="text-center py-3" style="width: 80px;">Rank</th>
                                    <th class="py-3">Nama Pupuk</th>
                                    <th class="text-end py-3">Nilai Max (Benefit)</th>
                                    <th class="text-end py-3">Nilai Min (Cost)</th>
                                    <th class="text-end py-3">Skor Akhir (Yi)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hasil_moora as $index => $data): $rank = $index + 1; ?>
                                <tr class="<?= ($rank == 1) ? 'table-success-subtle fw-semibold text-success-dark' : ''; ?>">
                                    <td class="text-center">
                                        <span
                                            class="badge <?= ($rank == 1) ? 'bg-success text-white' : 'bg-secondary-subtle text-dark'; ?> rounded-circle p-2 fs-6"
                                            style="width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;">
                                            <?= $rank; ?>
                                        </span>
                                    </td>
                                    <td class="fs-5 text-dark"><?= $data['nama_alternatif']; ?></td>
                                    <td class="text-end text-secondary"><?= number_format($data['max'], 4); ?></td>
                                    <td class="text-end text-secondary"><?= number_format($data['min'], 4); ?></td>
                                    <td class="text-end fw-bold fs-5 text-success"><?= number_format($data['yi'], 4); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Kolom Diagram Visualisasi -->
            <div class="col-lg-5">
                <div class="table-container shadow-sm h-100">
                    <h4 class="fw-bold text-dark mb-4">Visualisasi Skor Akhir (Yi)</h4>
                    <div style="position: relative; height: 350px;">
                        <canvas id="chartHasil"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php
    // Persiapan data untuk Chart.js
    $chart_labels = [];
    $chart_scores = [];
    if (!empty($hasil_moora)) {
        foreach ($hasil_moora as $data) {
            $chart_labels[] = $data['nama_alternatif'];
            $chart_scores[] = round($data['yi'], 4);
        }
    }
    ?>
    <script>
    document.getElementById('nav-dashboard').classList.add('active', 'bg-success');

    <?php if (!empty($hasil_moora)): ?>
    // Inisialisasi Chart.js
    const ctx = document.getElementById('chartHasil').getContext('2d');
    const chartLabels = <?= json_encode($chart_labels); ?>;
    const chartScores = <?= json_encode($chart_scores); ?>;
    
    // Warnai peringkat 1 dengan warna emas/kuning, yang lain dengan warna hijau brand
    const backgroundColors = chartScores.map((score, index) => {
        return index === 0 ? 'rgba(255, 193, 7, 0.85)' : 'rgba(25, 135, 84, 0.75)';
    });
    
    const borderColors = chartScores.map((score, index) => {
        return index === 0 ? 'rgba(255, 193, 7, 1)' : 'rgba(25, 135, 84, 1)';
    });

    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Skor Akhir (Yi)',
                data: chartScores,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1.5,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(33, 37, 41, 0.95)',
                    titleFont: { size: 13, weight: 'bold', family: 'Segoe UI' },
                    bodyFont: { size: 12, family: 'Segoe UI' },
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return ' Skor (Yi): ' + context.raw.toFixed(4);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            family: 'Segoe UI',
                            size: 11
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: 'Segoe UI',
                            weight: 'bold',
                            size: 12
                        }
                    }
                }
            }
        }
    });
    <?php endif; ?>
    </script>
</body>

</html>