<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Perbandingan Produk Pupuk - UD. APOLLO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        }

        .chart-box {
            max-height: 380px;
        }
    </style>
</head>

<body class="d-flex">

    <?php include 'includes/sidebar.php'; ?>

    <div class="flex-grow-1 p-5">
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark m-0">Komparasi Produk Pupuk</h1>
            <p class="text-secondary">Bandingkan head-to-head indeks kepuasan antara 2 alternatif pupuk buah
            </p>
        </div>

        <div class="table-container shadow-sm mb-4">
            <form method="GET" action="banding.php" class="row align-items-end g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-secondary">Pilih Produk Pertama (A)</label>
                    <select name="pupuk1" class="form-select form-select-lg border-2" required>
                        <option value="">-- Pilih Pupuk A --</option>
                        <?php foreach ($list_pilihan as $p): ?>
                            <option value="<?= $p['id_alternatif']; ?>" <?= (isset($_GET['pupuk1']) && $_GET['pupuk1'] == $p['id_alternatif']) ? 'selected' : ''; ?>>
                                <?= $p['nama_alternatif']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 text-center pb-2 fw-bold text-muted fs-4">VS</div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-secondary">Pilih Produk Kedua (B)</label>
                    <select name="pupuk2" class="form-select form-select-lg border-2" required>
                        <option value="">-- Pilih Pupuk B --</option>
                        <?php foreach ($list_pilihan as $p): ?>
                            <option value="<?= $p['id_alternatif']; ?>" <?= (isset($_GET['pupuk2']) && $_GET['pupuk2'] == $p['id_alternatif']) ? 'selected' : ''; ?>>
                                <?= $p['nama_alternatif']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold rounded-3">Analisis
                        Perbandingan</button>
                </div>
            </form>
        </div>

        <?php if ($show_result): ?>
            <div class="row g-4">

                <div class="col-lg-6">
                    <div class="table-container shadow-sm h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="fw-bold text-dark mb-3">Hasil Analisis MOORA</h4>
                            <hr class="text-muted">

                            <div class="d-flex justify-content-between align-items-center my-3 bg-light p-3 rounded-3">
                                <span class="fw-semibold text-secondary"><?= $data_p1['nama']; ?></span>
                                <span class="fs-5 fw-bold text-dark"><?= number_format($data_p1['yi'], 4); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center my-3 bg-light p-3 rounded-3">
                                <span class="fw-semibold text-secondary"><?= $data_p2['nama']; ?></span>
                                <span class="fs-5 fw-bold text-dark"><?= number_format($data_p2['yi'], 4); ?></span>
                            </div>
                        </div>

                        <div
                            class="alert <?= ($pemenang == 'seri') ? 'alert-info' : 'alert-success'; ?> border-0 p-3 m-0 rounded-3">
                            <h5 class="fw-bold mb-1">💡 Kesimpulan Rekomendasi:</h5>
                            <?php if ($pemenang == 'seri'): ?>
                                Kedua pupuk memiliki nilai optimasi yang **sama persis** berdasarkan parameter kriteria yang
                                ditentukan di wilayah Desa Pasar X. Petani bebas memilih di antara keduanya
                                sesuai ketersediaan stok.
                            <?php else: ?>
                                Berdasarkan evaluasi matematis, produk **<?= $pemenang['nama']; ?>** terbukti memiliki nilai
                                kecocokan yang lebih optimal dibandingkan dengan pesaingnya. Sangat
                                direkomendasikan untuk diprioritaskan oleh petani UD. APOLLO.
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="table-container shadow-sm h-100">
                        <h4 class="fw-bold text-dark mb-3">Grafik Pembobotan Parameter</h4>
                        <div class="chart-box d-flex align-items-center justify-content-center">
                            <canvas id="komparasiChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <script>
                const ctx = document.getElementById('komparasiChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        // Label diambil dinamis dari kode kriteria di database (C1, C2, C3, C4)
                        labels: <?php echo json_encode(array_values($kriteria)); ?>,
                        datasets: [{
                            label: '<?= $data_p1['nama']; ?>',
                            data: <?php echo json_encode($data_p1['chart_data']); ?>,
                            backgroundColor: 'rgba(25, 135, 84, 0.75)', // Hijau Emerald
                            borderColor: 'rgb(25, 135, 84)',
                            borderWidth: 1,
                            borderRadius: 6
                        },
                        {
                            label: '<?= $data_p2['nama']; ?>',
                            data: <?php echo json_encode($data_p2['chart_data']); ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.75)', // Biru Cerah
                            borderColor: 'rgb(54, 162, 235)',
                            borderWidth: 1,
                            borderRadius: 6
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Nilai Normalisasi Berbobot'
                                }
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('nav-banding').classList.add('active', 'bg-success');
    </script>
</body>

</html>