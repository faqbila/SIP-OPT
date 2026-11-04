<?php
require 'session_user.php';
include 'connect.php';

$where = [];

// OPT wajib diisi
if (!empty($_GET['opt'])) {
    $opt = mysqli_real_escape_string($koneksi, $_GET['opt']);
    $where[] = "opt LIKE '%$opt%'";
} else {
    // Jika OPT kosong, tampilkan semua data
    $where[] = "1=1";
}

// Filter tambahan (opsional)
if (!empty($_GET['tahun'])) {
    $tahun = (int)$_GET['tahun'];
    $where[] = "tahun = $tahun";
}

if (!empty($_GET['triwulan'])) {
    $triwulan = (int)$_GET['triwulan'];
    $where[] = "triwulan = $triwulan";
}

if (!empty($_GET['provinsi'])) {
    $provinsi = mysqli_real_escape_string($koneksi, $_GET['provinsi']);
    $where[] = "provinsi LIKE '%$provinsi%'";
}


$whereSQL = 'WHERE ' . implode(' AND ', $where);


// Ambil semua komoditas sesuai filter
$queryKomoditas = "
    SELECT komoditas, 
           SUM(total_luas) AS total_luas,
           SUM(luas_ringan) AS total_ringan,
           SUM(luas_berat) AS total_berat
    FROM serangan
    $whereSQL
    GROUP BY komoditas
    ORDER BY total_luas DESC
";
$resultKomoditas = mysqli_query($koneksi, $queryKomoditas);

$komoditasData  = [];
$totalLuasData  = [];
$luasRinganData = [];
$luasBeratData  = [];

while ($row = mysqli_fetch_assoc($resultKomoditas)) {
    $komoditasData[]  = $row['komoditas'];
    $totalLuasData[]  = $row['total_luas'];
    $luasRinganData[] = $row['total_ringan'];
    $luasBeratData[]  = $row['total_berat'];
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="logo2.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proteksi BBPPTP</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="tampilan.css">

    <!-- JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <img src="logo2.png" alt="Logo" style="width: 100px; margin-bottom: 15px; margin-left: 60px;">
    <h5 class="text-center">OPT BBPPTP MEDAN</h5>
    <a href="home_user.php"><i class="bi bi-house"></i> Beranda</a>
    <a href="serangan.php"><i class="bi bi-bug"></i> Kabupaten</a>
    <a href="jenis_opt.php"><i class="bi bi-briefcase"></i></i> Provinsi </a>
    <a href="provinsi_opt.php"><i class="bi bi-buildings"></i> Provinsi & OPT </a>
    <a href="komoditas.php"><i class="bi bi-tree-fill"></i> OPT/Komoditas </a> 
    <a href="grafik_opt.php"><i class="bi bi-archive"></i> Grafik OPT Provinsi</a>
    <a href="forecast.php" class="fw-bold"><i class="bi bi-graph-up-arrow"></i> Peramalan</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Konten -->
<div class="content">
    
    <!-- Filter Form -->
    <form method="get" class="bg-light p-3 mb-3">
        <div class="row g-2 align-items-end">

            <!-- Tahun -->
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select form-select-sm">
                    <option value="">-- Semua Tahun --</option>
                    <?php
                    $tahunQuery = mysqli_query($koneksi, "SELECT DISTINCT tahun FROM serangan ORDER BY tahun DESC");
                    while ($th = mysqli_fetch_assoc($tahunQuery)) {
                        $selected = ($_GET['tahun'] ?? '') == $th['tahun'] ? 'selected' : '';
                        echo "<option value='{$th['tahun']}' $selected>{$th['tahun']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Triwulan -->
            <div class="col-md-2">
                <label class="form-label">Triwulan</label>
                <select name="triwulan" class="form-select form-select-sm">
                    <option value="">-- Semua --</option>
                    <?php
                    for ($i = 1; $i <= 4; $i++) {
                        $selected = ($_GET['triwulan'] ?? '') == $i ? 'selected' : '';
                        echo "<option value='$i' $selected>Triwulan $i</option>";
                    }
                    ?>
                </select>
            </div>

       <!-- Provinsi -->
<div class="col-md-2">
    <label class="form-label">Provinsi</label>
    <select name="provinsi" class="form-select form-select-sm">
        <option value="">-- Semua Provinsi --</option>
        <?php
        $provQuery = mysqli_query($koneksi, "SELECT DISTINCT provinsi FROM serangan ORDER BY provinsi ASC");
        while ($prov = mysqli_fetch_assoc($provQuery)) {
            $selected = ($_GET['provinsi'] ?? '') == $prov['provinsi'] ? 'selected' : '';
            echo "<option value='{$prov['provinsi']}' $selected>{$prov['provinsi']}</option>";
        }
        ?>
    </select>
</div>


            <!-- OPT -->
            <div class="col-md-2">
                <label class="form-label">OPT</label>
                <select name="opt" class="form-select form-select-sm">
                    <option value="">-- Semua OPT --</option>
                    <?php
                    $optQuery = mysqli_query($koneksi, "SELECT DISTINCT opt FROM serangan ORDER BY opt ASC");
                    while ($optData = mysqli_fetch_assoc($optQuery)) {
                        $selected = ($_GET['opt'] ?? '') == $optData['opt'] ? 'selected' : '';
                        echo "<option value='{$optData['opt']}' $selected>{$optData['opt']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Tombol -->
            <div class="col-md-2 d-flex flex-row align-items-center">
                <button type="submit" class="btn btn-primary btn-sm me-2 w-50">
                    <i class="bi bi-filter"></i> Filter
                </button>
                <a href="serangan.php" class="btn btn-secondary btn-sm w-50">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
        </div>
    </form>

   <!-- Grafik -->
<div class="container-fluid">
    <h4 class="text-center my-4">
        Grafik Serangan OPT di 10 Provinsi Terbesar
        <?php 
            echo !empty($_GET['tahun']) ? " Tahun " . htmlspecialchars($_GET['tahun']) : "";
            echo !empty($_GET['triwulan']) ? " - Triwulan " . htmlspecialchars($_GET['triwulan']) : "";
            echo !empty($_GET['komoditas']) ? " - " . htmlspecialchars($_GET['komoditas']) : "";
            echo !empty($_GET['opt']) ? " - OPT " . htmlspecialchars($_GET['opt']) : "";
        ?>
    </h4>
    <div class="px-3">
        <canvas id="grafikBar" height="120"></canvas>
    </div>
    <div class="text-end my-3 me-3">
    <form id="exportForm" method="POST" action="export_komoditas.php" target="_blank">
        <input type="hidden" name="barImg" id="barImg">

        <!-- Kirim filter -->
        <input type="hidden" name="tahun" value="<?php echo htmlspecialchars($_GET['tahun'] ?? ''); ?>">
        <input type="hidden" name="triwulan" value="<?php echo htmlspecialchars($_GET['triwulan'] ?? ''); ?>">
        <input type="hidden" name="provinsi" value="<?php echo htmlspecialchars($_GET['provinsi'] ?? ''); ?>">
        <input type="hidden" name="opt" value="<?php echo htmlspecialchars($_GET['opt'] ?? ''); ?>">

        <!-- Data tabel -->
        <input type="hidden" name="komoditasData" id="komoditasData">

        <button type="submit" class="btn btn-success">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </button>
    </form>
</div>



</div>


<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafikBar').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($komoditasData); ?>,
        datasets: [
           
            {
                label: 'Luas Ringan (Ha)',
                data: <?php echo json_encode($luasRinganData); ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            },
            {
                label: 'Luas Berat (Ha)',
                data: <?php echo json_encode($luasBeratData); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            },
             {
                label: 'Total Luas (Ha)',
                data: <?php echo json_encode($totalLuasData); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }
        ]
    },
 options: {
    responsive: true,
    plugins: {
        legend: { display: true },
        tooltip: { mode: 'index', intersect: false }
    },
    scales: {
        x: {
            ticks: {
                autoSkip: false,
                maxRotation: 90,
                minRotation: 45
            }
        },
        y: { beginAtZero: true }
    }
}

});

</script>
<script>
// Tangkap grafik saat tombol Export ditekan
document.getElementById("exportForm").addEventListener("submit", function(e) {
    const canvas = document.getElementById("grafikBar");
    document.getElementById("barImg").value = canvas.toDataURL("image/png");

    // Ambil data tabel (dari PHP → array → JSON → simpan ke hidden input)
    const tableData = <?php
        $tableArr = [];
        $resultKomoditas = mysqli_query($koneksi, $queryKomoditas);
        while ($row = mysqli_fetch_assoc($resultKomoditas)) {
            $tableArr[] = $row;
        }
        echo json_encode($tableArr);
    ?>;
   document.getElementById("komoditasData").value = JSON.stringify(tableData);
});
</script>

</body>
</html>
