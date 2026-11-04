<?php
require 'session_admin.php';
include 'connect.php';

if (!isset($_GET['provinsi'])) {
    echo "Provinsi tidak ditemukan.";
    exit;
}

$provinsi   = mysqli_real_escape_string($koneksi, $_GET['provinsi']);
$tahun      = isset($_GET['tahun']) ? mysqli_real_escape_string($koneksi, $_GET['tahun']) : '';
$triwulan   = isset($_GET['triwulan']) ? mysqli_real_escape_string($koneksi, $_GET['triwulan']) : '';
$komoditas  = isset($_GET['komoditas']) ? mysqli_real_escape_string($koneksi, $_GET['komoditas']) : '';
$cari       = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : '';

$where = "WHERE provinsi = '$provinsi'";

// Filter tahun
if (!empty($tahun)) {
    $where .= " AND tahun = '$tahun'";
}

// Filter triwulan
if (!empty($triwulan)) {
    $where .= " AND triwulan = '$triwulan'";
}

// Filter komoditas
if (!empty($komoditas)) {
    $where .= " AND komoditas = '$komoditas'";
}

// Filter pencarian
if (!empty($cari)) {
    $where .= " AND (kabupaten LIKE '%$cari%' OR komoditas LIKE '%$cari%' OR opt LIKE '%$cari%')";
}

// Query ambil data detail
$query = "SELECT * FROM serangan 
$where 
ORDER BY tahun DESC, triwulan DESC, opt ASC, kabupaten ASC";

$result = mysqli_query($koneksi, $query);

// Ambil daftar tahun unik
$tahunResult = mysqli_query($koneksi, "SELECT DISTINCT tahun FROM serangan ORDER BY tahun DESC");

// Ambil daftar triwulan unik
$triwulanResult = mysqli_query($koneksi, "SELECT DISTINCT triwulan FROM serangan ORDER BY triwulan ASC");

// Ambil daftar komoditas unik
$komoditasResult = mysqli_query($koneksi, "SELECT DISTINCT komoditas FROM serangan WHERE provinsi = '$provinsi' ORDER BY komoditas ASC");
$provinsiResult = mysqli_query($koneksi, "SELECT DISTINCT provinsi FROM serangan ORDER BY provinsi ASC");

?>
<?php
// ==============================
// Query Data untuk Grafik Batang
// ==============================

// ==============================
// QUERY FIX (SUDAH BENAR)
// ==============================
$chartQuery = "
    SELECT opt, tahun, triwulan, SUM(total_luas) AS total 
    FROM serangan 
    $where
    GROUP BY opt, tahun, triwulan
    ORDER BY tahun, FIELD(triwulan,'I','II','III','IV')
";

$chartResult = mysqli_query($koneksi, $chartQuery);

// ==============================
// SUSUN DATA
// ==============================
$optList = [];
$dataMap = [];
$labels = [];

while ($row = mysqli_fetch_assoc($chartResult)) {
    $opt = $row['opt'];
    $label = $row['tahun'] . '-' . $row['triwulan']; // contoh: 2018-IV

    $optList[$opt] = true;
    $labels[$label] = true;

    $dataMap[$opt][$label] = $row['total'];
}

// ==============================
// LABEL X (WAKTU)
// ==============================
$labels = array_keys($labels);
sort($labels);
if(empty($labels)){
    $labels = ["Tidak ada data"];
}

// ==============================
// DATASET (PER OPT)
// ==============================
$datasets = [];

foreach ($optList as $opt => $v) {
    $data = [];

    foreach ($labels as $label) {
        $data[] = $dataMap[$opt][$label] ?? 0;
    }

    $datasets[] = [
        "label" => $opt,
        "data" => $data,
        "backgroundColor" => '#' . substr(md5($opt), 0, 6)
    ];
}
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
    <a href="data_opt.php"><i class="bi bi-database"></i> Data OPT </a>
    <a href="users.php"><i class="bi bi-people"></i> Pengguna </a>
    <a href="wilayah.php" class="fw-bold"><i class="bi bi-building"></i> Wilayah</a>
    <a href="forecast_admin.php" class="fw-bold"><i class="bi bi-graph-up-arrow"></i> Peramalan</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
<!-- Konten -->
<div class="content">


    <!-- Filter Tahun, Triwulan, Komoditas, Pencarian -->
    <div class="d-flex justify-content-between align-items-center p-2 bg-light flex-wrap gap-2">
        <!-- Form Filter Tahun, Triwulan, Komoditas -->
<!-- Filter Provinsi, Tahun, Triwulan, Komoditas, dan Pencarian -->
<form method="get" class="d-flex flex-wrap align-items-center gap-2 p-2 bg-light rounded">

    <!-- Dropdown Provinsi -->
    <select name="provinsi" class="form-select form-select-sm" style="width: 180px;">
        <option value="">Semua Provinsi</option>
        <?php while ($rowProv = mysqli_fetch_assoc($provinsiResult)) { 
            $selected = ($provinsi == $rowProv['provinsi']) ? 'selected' : '';
            echo "<option value='{$rowProv['provinsi']}' $selected>{$rowProv['provinsi']}</option>";
        } ?>
    </select>

    <!-- Dropdown Tahun -->
    <select name="tahun" class="form-select form-select-sm" style="width: 150px;">
        <option value="">Semua Tahun</option>
        <?php while ($rowTahun = mysqli_fetch_assoc($tahunResult)) { 
            $selected = ($tahun == $rowTahun['tahun']) ? 'selected' : '';
            echo "<option value='{$rowTahun['tahun']}' $selected>{$rowTahun['tahun']}</option>";
        } ?>
    </select>

    <!-- Dropdown Triwulan -->
    <select name="triwulan" class="form-select form-select-sm" style="width: 150px;">
        <option value="">Semua Triwulan</option>
        <?php while ($rowTri = mysqli_fetch_assoc($triwulanResult)) { 
            $selected = ($triwulan == $rowTri['triwulan']) ? 'selected' : '';
            echo "<option value='{$rowTri['triwulan']}' $selected>Triwulan {$rowTri['triwulan']}</option>";
        } ?>
    </select>

    <!-- Dropdown Komoditas -->
    <select name="komoditas" class="form-select form-select-sm" style="width: 180px;">
        <option value="">Semua Komoditas</option>
        <?php while ($rowKom = mysqli_fetch_assoc($komoditasResult)) { 
            $selected = ($komoditas == $rowKom['komoditas']) ? 'selected' : '';
            echo "<option value='{$rowKom['komoditas']}' $selected>{$rowKom['komoditas']}</option>";
        } ?>
    </select>

    <!-- Tombol Cari -->
    <button type="submit" class="btn btn-primary btn-sm">
        <i class="bi bi-search"></i> Cari
    </button>

    <!-- Tombol Kembali -->
    <a href="provinsi.php" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</form>

<!-- Tombol Kembali -->


        <!-- Form Pencarian -->
        <form method="get" class="d-flex gap-2" style="max-width: 350px;">
            <input type="hidden" name="provinsi" value="<?= htmlspecialchars($provinsi) ?>">
            <input type="hidden" name="tahun" value="<?= htmlspecialchars($tahun) ?>">
            <input type="hidden" name="triwulan" value="<?= htmlspecialchars($triwulan) ?>">
            <input type="hidden" name="komoditas" value="<?= htmlspecialchars($komoditas) ?>">
            <input type="text" name="cari" class="form-control form-control-sm"  
                   placeholder="Cari kabupaten, komoditas, atau OPT" 
                   value="<?= htmlspecialchars($cari) ?>">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

     <p>
        Data Serangan Provinsi: <b><?= htmlspecialchars($provinsi) ?></b> 
        <?= $tahun ? "(Tahun $tahun)" : "(Semua Tahun)" ?>
        <?= $triwulan ? " - Triwulan $triwulan" : "" ?>
        <?= $komoditas ? " - Komoditas $komoditas" : "" ?>
    </p>

    <table class="table table-bordered table-striped">
        <thead class="table-secondary">
            <tr>
                <th>No</th>
                <th>Tahun</th>
                <th>Triwulan</th>
                <th>Kabupaten</th>
                <th>Komoditas</th>
                <th>OPT</th>
                <th>Total Luas (Ha)</th>
                <th style="width:140px;" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
           <?php 
           $no = 1;
           while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $row['tahun']; ?></td>
    <td><?= $row['triwulan']; ?></td>
    <td><?= $row['kabupaten']; ?></td>
    <td><?= $row['komoditas']; ?></td>
    <td><?= $row['opt']; ?></td>
    <td><?= $row['total_luas']; ?></td>
    <td>
        <button class="btn btn-warning btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#editModal<?= $row['id']; ?>">
           <i class="bi bi-pencil-square"></i>
        </button>

        <a href="hapus_serangan.php?id=<?= $row['id']; ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Yakin ingin menghapus data ini?')">
         <i class="bi bi-trash"></i>
        </a>
    </td>
</tr>
<!-- Modal Edit -->
<div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="update_serangan.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Edit Data Serangan
                    </h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden"
                           name="id"
                           value="<?= $row['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Kabupaten</label>
                        <input type="text"
                               name="kabupaten"
                               class="form-control"
                               value="<?= $row['kabupaten']; ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Komoditas</label>
                        <input type="text"
                               name="komoditas"
                               class="form-control"
                               value="<?= $row['komoditas']; ?>"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
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
