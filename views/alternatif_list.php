<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Alternatif - UD. APOLLO</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark m-0">Kelola Alternatif</h1>
                <p class="text-secondary">Manajemen data pupuk serta nilai parameter kecocokan</p>
            </div>
            <a href="form_alternatif.php" class="btn btn-success px-4 py-2 rounded-3 fw-semibold">+ Tambah Pupuk</a>
        </div>

        <div class="table-container shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light text-secondary small text-uppercase text-center">
                        <tr>
                            <th class="py-3" style="width: 70px;">No</th>
                            <th class="py-3 text-start">Nama Pupuk</th>
                            <?php foreach ($list_kriteria as $k): ?>
                            <th class="py-3" title="<?= $k['nama_kriteria']; ?>"><?= $k['kode_kriteria']; ?>
                            </th>
                            <?php endforeach; ?>
                            <th class="py-3" style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1; 
                        while ($alt = $alternatif_res->fetch_assoc()): 
                            $id_alt = $alt['id_alternatif']; 
                        ?>
                        <tr>
                            <td class="text-center text-secondary"><?= $no++; ?></td>
                            <td class="fw-semibold text-dark"><?= $alt['nama_alternatif']; ?></td>

                            <?php 
                            foreach ($list_kriteria as $k) {
                                $id_k = $k['id_kriteria'];
                                $nilai_res = $conn->query("SELECT nilai FROM matriks_keputusan WHERE id_alternatif = $id_alt AND id_kriteria = $id_k");
                                $data_nilai = $nilai_res->fetch_assoc();
                                $nilai = $data_nilai ? $data_nilai['nilai'] : '-';
                                echo "<td class='text-center fs-5 text-muted'>$nilai</td>";
                            }
                            ?>

                            <td class="text-center">
                                <a href="form_alternatif.php?edit=<?= $id_alt; ?>"
                                    class="btn btn-outline-warning btn-sm px-3 rounded-2 me-1">Edit</a>
                                <a href="alternatif.php?hapus=<?= $id_alt; ?>"
                                    class="btn btn-outline-danger btn-sm px-3 rounded-2"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pupuk ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('nav-alternatif').classList.add('active', 'bg-success');
    </script>
</body>

</html>