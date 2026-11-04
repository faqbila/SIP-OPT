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

if (!empty($_GET['komoditas'])) {
    $komoditas = mysqli_real_escape_string($koneksi, $_GET['komoditas']);
    $where[] = "komoditas LIKE '%$komoditas%'";
}

$whereSQL = 'WHERE ' . implode(' AND ', $where);


// Ambil 10 provinsi terbesar
$queryProvinsi = "
    SELECT provinsi, 
           SUM(total_luas) AS total_luas,
           SUM(luas_ringan) AS total_ringan,
           SUM(luas_berat) AS total_berat
    FROM serangan
    $whereSQL
    GROUP BY provinsi
    ORDER BY total_luas DESC
    LIMIT 10
";
$resultProvinsi = mysqli_query($koneksi, $queryProvinsi);

$provinsiData   = [];
$totalLuasData  = [];
$luasRinganData = [];
$luasBeratData  = [];

while ($row = mysqli_fetch_assoc($resultProvinsi)) {
    $provinsiData[]   = $row['provinsi'];
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

           <!-- Komoditas -->
<div class="col-md-2">
    <label class="form-label">Komoditas</label>
    <select name="komoditas" id="komoditasSelect" class="form-select form-select-sm">
        <option value="">-- Semua Komoditas --</option>
        <?php
        $komoQuery = mysqli_query($koneksi, "SELECT DISTINCT komoditas FROM serangan ORDER BY komoditas ASC");
        while ($komo = mysqli_fetch_assoc($komoQuery)) {
            $selected = ($_GET['komoditas'] ?? '') == $komo['komoditas'] ? 'selected' : '';
            echo "<option value='{$komo['komoditas']}' $selected>{$komo['komoditas']}</option>";
        }
        ?>
    </select>
</div>

<!-- OPT -->
<div class="col-md-2">
    <label class="form-label">OPT</label>
    <select name="opt" id="optSelect" class="form-select form-select-sm">
        <option value="">-- Semua OPT --</option>
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
        Grafik Serangan OPT 10 Provinsi di Wilayah Kerja
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
<!-- Tombol Export -->
<form id="exportForm" method="post" action="export_provinsiopt.php" target="_blank">
    <!-- Hidden input untuk gambar grafik -->
    <input type="hidden" name="barImg" id="barImg">

    <!-- Hidden input untuk data total_luas per provinsi -->
    <input type="hidden" name="provinsiData" id="provinsiData" value='<?= json_encode(
    array_map(null, $provinsiData, $luasRinganData, $luasBeratData, $totalLuasData)
) ?>'>


    <!-- Hidden input untuk filter yang dipakai -->
    <input type="hidden" name="tahun" id="tahunFilter" value="<?= htmlspecialchars($tahun ?? '') ?>">
    <input type="hidden" name="triwulan" id="triwulanFilter" value="<?= htmlspecialchars($triwulan ?? '') ?>">
    <input type="hidden" name="komoditas" id="komoditasFilter" value="<?= htmlspecialchars($komoditas ?? '') ?>">
    <input type="hidden" name="opt" id="optFilter" value="<?= htmlspecialchars($opt ?? '') ?>">

    <div style="text-align: right; margin-top:10px;">
        <button type="submit" class="btn btn-success">Export PDF</button>
    </div>
</form>

<script>
const canvasBar = document.getElementById('grafikBar');
const exportForm = document.getElementById('exportForm');

exportForm.addEventListener('submit', function() {
    const imgBar = canvasBar.toDataURL("image/png");
    document.getElementById('barImg').value = imgBar;
});
</script>



</div>


<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafikBar').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($provinsiData); ?>,
        datasets: [
            {
                label: 'Luas Ringan (Ha)',
                data: <?php echo json_encode($luasRinganData); ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.7)',
                stack: 'serangan'
            },
            {
                label: 'Luas Berat (Ha)',
                data: <?php echo json_encode($luasBeratData); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                stack: 'serangan'
            }
        ]
    },
    options: {
        indexAxis: 'y', // 🔹 Membalik sumbu jadi horizontal
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            x: {
                stacked: true,
                title: { display: true, text: 'Luas Serangan (Ha)' }
            },
            y: {
                stacked: true,
                title: { display: true, text: 'Provinsi' }
            }
        }
    }
});

</script>
<script>
    document.getElementById("exportBtn").addEventListener("click", function () {
    const canvas = document.getElementById("barChart");
    const barImg = canvas.toDataURL("image/png");

    // Data total luas serangan per provinsi
    const provinsiData = <?= json_encode($dataProvinsi) ?>; // contoh: [["Aceh",120],["Sumut",200]]

    const form = document.createElement("form");
    form.method = "POST";
    form.action = "export_jenisopt.php";
    form.target = "_blank";

    form.innerHTML = `
        <input type="hidden" name="barImg" value="${barImg}">
        <input type="hidden" name="tahun" value="<?= $tahun ?>">
        <input type="hidden" name="triwulan" value="<?= $triwulan ?>">
        <input type="hidden" name="komoditas" value="<?= $komoditas ?>">
        <input type="hidden" name="opt" value="<?= $opt ?>">
        <input type="hidden" name="provinsiData" value='${JSON.stringify(provinsiData)}'>
    `;
    document.body.appendChild(form);
    form.submit();
    form.remove();
});

</script>
<script>
// Ambil data grafik dari Chart.js
function prepareExport() {
    // Ambil canvas chart
    let canvas = document.getElementById("barChart"); // ID canvas grafik
    let barImg = canvas.toDataURL("image/png");

    // Simpan gambar ke hidden input
    document.getElementById("barImg").value = barImg;

    // Ambil data provinsi + total_luas dari chart
    let provinsi = chart.data.labels; // label = nama provinsi
    let luasData = chart.data.datasets[0].data; // data = total luas

    let result = [];
    for (let i = 0; i < provinsi.length; i++) {
        result.push([provinsi[i], luasData[i]]);
    }

    // Simpan ke hidden input sebagai JSON
    document.getElementById("provinsiData").value = JSON.stringify(result);
}

// Tangani submit form
document.getElementById("exportForm").addEventListener("submit", function() {
    const canvasBar = document.getElementById("grafikBar");
    document.getElementById("barImg").value = canvasBar.toDataURL("image/png");

    // Ambil data dari PHP yang sudah dimasukkan ke hidden input
    let provinsiData = <?= json_encode(
        array_map(null, $provinsiData, $luasRinganData, $luasBeratData, $totalLuasData)
    ) ?>;

    // Simpan ke hidden input dalam format JSON
    document.getElementById("provinsiData").value = JSON.stringify(provinsiData);
});

</script>
<script>
// Event ketika form export ditekan
document.getElementById("exportForm").addEventListener("submit", function() {
    // Ambil gambar dari canvas Chart.js
    let canvas = document.getElementById("barChart"); 
    let barImg = canvas.toDataURL("image/png");
    document.getElementById("barImg").value = barImg;

    // Ambil data provinsi dan total_luas dari grafik
    let provinsi = chart.data.labels;               // label grafik = provinsi
    let luasData = chart.data.datasets[0].data;     // dataset grafik = total luas

    let result = [];
    for (let i = 0; i < provinsi.length; i++) {
        result.push([provinsi[i], luasData[i]]);
    }

    // Simpan ke hidden input sebagai JSON
    document.getElementById("provinsiData").value = JSON.stringify(result);
});
</script>
<script>
$(function() {
    // Saat komoditas berubah → ambil daftar OPT
    $('#komoditasSelect').on('change', function() {
        let komoditas = $(this).val();
        $.getJSON('get_opt.php', { komoditas: komoditas }, function(data) {
            let $optSelect = $('#optSelect');
            $optSelect.empty().append('<option value="">-- Semua OPT --</option>');
            $.each(data, function(i, opt) {
                let selected = (opt === "<?= $_GET['opt'] ?? '' ?>") ? 'selected' : '';
                $optSelect.append('<option value="'+opt+'" '+selected+'>'+opt+'</option>');
            });
        });
    });

    // Jalankan sekali waktu halaman pertama kali load
    $('#komoditasSelect').trigger('change');
});
</script>


</body>
</html>
