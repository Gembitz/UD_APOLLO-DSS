<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tahapan Proses Perhitungan MOORA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
    </style>
</head>

<body class="d-flex">

    <?php include 'includes/sidebar.php'; ?>

    <div class="flex-grow-1 p-5">
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark m-0">Log Perhitungan Metode MOORA</h1>
            <p class="text-secondary">Transparansi data tahapan rumus matematis berdasarkan data artikel jurnal</p>
        </div>

        <?php if (empty($alternatif)): ?>
        <div class="alert alert-warning border-0 p-4 rounded-3 shadow-sm">Data alternatif kosong.</div>
        <?php else: ?>

        <div class="table-container shadow-sm mb-5">
            <div class="d-flex align-items-center mb-3">
                <span class="badge bg-success me-2 fs-6">Langkah 1</span>
                <h4 class="fw-bold text-dark m-0">Matriks Normalisasi MOORA</h4>
            </div>
            <p class="text-muted small">Setiap nilai mentah dibagi dengan nilai akar jumlah kuadrat kriteria.</p>
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light text-secondary small text-uppercase text-center">
                        <tr>
                            <th class="py-3 text-start">Nama Alternatif</th>
                            <?php foreach ($list_kriteria as $k): ?>
                            <th class="py-3"><?= $k['kode_kriteria']; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alternatif as $id_alt => $nama): ?>
                        <tr>
                            <td class="fw-semibold text-dark"><?= $nama; ?></td>
                            <?php foreach ($list_kriteria as $k): $id_k = $k['id_kriteria']; ?>
                            <td class="text-center text-muted">
                                <?= number_format($matriks_normalisasi[$id_alt][$id_k], 4); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="table-container shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <span class="badge bg-success me-2 fs-6">Langkah 2</span>
                <h4 class="fw-bold text-dark m-0">Nilai Optimasi Multiobjektif MOORA</h4>
            </div>
            <p class="text-muted small">Nilai normalisasi dikali bobot, kemudian dikelompokkan menjadi total nilai
                keuntungan (Maximum) dikurangi total nilai biaya (Minimum).</p>
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light text-secondary small text-uppercase text-center">
                        <tr>
                            <th class="py-3 text-start">Nama Alternatif</th>
                            <th class="py-3 text-end">Total Maximum (C1+C3+C4)</th>
                            <th class="py-3 text-end">Total Minimum (C2)</th>
                            <th class="py-3 text-end">Nilai Akhir Yi (Max - Min)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tabel_optimasi_akhir as $data): ?>
                        <tr>
                            <td class="fw-semibold text-dark"><?= $data['nama_alternatif']; ?></td>
                            <td class="text-end text-secondary"><?= number_format($data['max'], 4); ?></td>
                            <td class="text-end text-secondary"><?= number_format($data['min'], 4); ?></td>
                            <td class="text-end fw-bold text-success fs-5"><?= number_format($data['yi'], 4); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php endif; ?>
    </div>

    <script>
    document.getElementById('nav-proses').classList.add('active', 'bg-success');
    </script>
</body>

</html>