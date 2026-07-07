<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $is_edit ? 'Edit' : 'Tambah'; ?> Alternatif</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', sans-serif;
    }

    .form-card {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }
    </style>
</head>

<body class="d-flex">

    <?php include 'includes/sidebar.php'; ?>

    <div class="flex-grow-1 p-5 d-flex justify-content-center align-items-start">
        <div class="form-card w-100" style="max-width: 650px;">
            <h3 class="fw-bold text-dark mb-1"><?= $is_edit ? 'Ubah Informasi Pupuk' : 'Tambah Produk Baru'; ?></h3>
            <p class="text-secondary mb-4">Lengkapi form nama produk dan nilai parameter di bawah ini</p>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">Nama Produk Pupuk</label>
                    <input type="text" name="nama_alternatif"
                        class="form-control form-control-lg bg-light border-0 px-3" value="<?= $nama_alt; ?>" required
                        placeholder="Masukkan nama pupuk...">
                </div>

                <h5 class="fw-bold text-dark mt-4 mb-2">Penilaian Parameter (Skala 1 - 5)</h5> [cite: 165]
                <p class="text-muted small mb-3">Sesuaikan nilai kriteria berdasarkan standar konversi tabel
                    jurnal[cite: 163].</p>

                <div class="row g-3">
                    <?php foreach ($list_kriteria as $k) : 
                        $id_k = $k['id_kriteria'];
                        $val = isset($nilai_lama[$id_k]) ? $nilai_lama[$id_k] : '';
                    ?>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-semibold text-secondary mb-1">
                            <?= $k['kode_kriteria']; ?> - <?= $k['nama_kriteria']; ?> [cite: 160]
                            <?= statusBadge($k['atribut']); ?>
                        </label>
                        <input type="number" step="any" name="nilai_kriteria[<?= $id_k; ?>]"
                            class="form-control bg-light border-0" value="<?= $val; ?>" min="1" max="5" required
                            placeholder="Nilai (1-5)">
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex gap-2 mt-5">
                    <button type="submit" class="btn btn-success px-4 py-2 rounded-3 fw-semibold flex-grow-1">Simpan
                        Data</button>
                    <a href="alternatif.php" class="btn btn-light px-4 py-2 rounded-3 text-secondary border">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>