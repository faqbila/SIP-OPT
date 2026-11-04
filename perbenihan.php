<?php
include 'konek.php';

$query = mysqli_query($koneksi, "
    SELECT *
    FROM tb_sertifikasi_penguna_benih
    WHERE file_sertifikat IS NOT NULL
      AND file_sertifikat != ''
      AND lhp IS NOT NULL
      AND lhp != ''
    LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="logo2.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proteksi BBPPTP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="tampilan.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <img src="logo2.png" alt="Logo" style="width: 100px; margin-bottom: 15px; margin-left: 60px;">
    <h5 class="text-center">OPT BBPPTP MEDAN</h5>
    <a href="home_admin.php"><i class="bi bi-house"></i> Beranda</a>
    <a href="data_opt.php"><i class="bi bi-bank"></i> Data OPT </a>
    <a href="users.php"><i class="bi bi-bank"></i> Pengguna </a>
    <a href="forecast_admin.php" class="fw-bold"><i class="bi bi-graph-up-arrow"></i> Peramalan</a>
    <a href="perbenihan.php" class="fw-bold"><i class="bi bi-graph-up-arrow"></i> Perbenihan</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Konten -->
<div class="content">

<!-- Tombol Kembali -->

    <table class="table table-bordered table-striped">
        <thead class="table-secondary">
            <tr>
                <th>No</th>
               <th class="text-center">Sertifikat</th>
                <th class="text-center">LHP</th>
            </tr>
        </thead>
        <tbody>
           <?php 
$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
?>
<tr>
    <td><?= $no++; ?></td>
    <!-- FILE SERTIFIKAT -->
    <td class="text-center">
        <?php if (!empty($row['file_sertifikat'])) { ?>
            <a href="uploads/sertifikat/<?= $row['file_sertifikat']; ?>"
               target="_blank"
               class="btn btn-success btn-sm"
               title="Lihat Sertifikat">
                <i class="bi bi-file-earmark-pdf-fill"></i>
            </a>
        <?php } else { ?>
            <span class="badge bg-secondary">Tidak Ada</span>
        <?php } ?>
    </td>

    <!-- FILE LHP -->
    <td class="text-center">
        <?php if (!empty($row['lhp'])) { ?>
            <a href="uploads/lhp/<?= $row['lhp']; ?>"
               target="_blank"
               class="btn btn-info btn-sm"
               title="Lihat LHP">
                <i class="bi bi-file-earmark-text-fill"></i>
            </a>
        <?php } else { ?>
            <span class="badge bg-secondary">Tidak Ada</span>
        <?php } ?>
    </td>
</tr>

    </div>
</div>
<?php } ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('optChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: <?= json_encode($datasets) ?>
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: {
                display: true,
                text: 'Total Luas Serangan OPT (Ha) berdasarkan Triwulan'
            }
        },
        scales: {
            x: { stacked: false },
            y: { beginAtZero: true }
        }
    }
});
</script>
</body>
</html>
