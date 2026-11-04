<?php
require 'session_user.php';
include 'connect.php';

// Ambil daftar provinsi untuk filter
$provList = [];
$provQuery = mysqli_query($koneksi, "SELECT DISTINCT provinsi FROM serangan ORDER BY provinsi ASC");
while ($p = mysqli_fetch_assoc($provQuery)) {
    $provList[] = $p['provinsi'];
}

// Ambil filter dari request
$komoditas       = $_GET['komoditas'] ?? '';
$opt             = $_GET['opt'] ?? '';
$provinsiDipilih = $_GET['provinsi'] ?? [];
$tahunAwal       = $_GET['tahun_awal'] ?? '';
$tahunAkhir      = $_GET['tahun_akhir'] ?? '';
$triwulan        = $_GET['triwulan'] ?? '';

// Bangun klausa WHERE
$where = [];
if (!empty($komoditas)) {
    $where[] = "komoditas = '" . mysqli_real_escape_string($koneksi, $komoditas) . "'";
}
if (!empty($opt)) {
    $where[] = "opt = '" . mysqli_real_escape_string($koneksi, $opt) . "'";
}
if (!empty($provinsiDipilih)) {
    $provIn = array_map(fn($p) => "'" . mysqli_real_escape_string($koneksi, $p) . "'", $provinsiDipilih);
    $where[] = "provinsi IN (" . implode(",", $provIn) . ")";
}
if (!empty($tahunAwal)) {
    $where[] = "tahun >= '" . mysqli_real_escape_string($koneksi, $tahunAwal) . "'";
}
if (!empty($tahunAkhir)) {
    $where[] = "tahun <= '" . mysqli_real_escape_string($koneksi, $tahunAkhir) . "'";
}
if (!empty($triwulan)) {
    $where[] = "triwulan = '" . mysqli_real_escape_string($koneksi, $triwulan) . "'";
}

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// Ambil data total_luas
$sql = "
    SELECT CONCAT(tahun,'-T',triwulan) AS periode, provinsi, SUM(total_luas) AS total_luas
    FROM serangan
    $whereSQL
    GROUP BY tahun, triwulan, provinsi
    ORDER BY tahun ASC, triwulan ASC
";
$result = mysqli_query($koneksi, $sql);

// Siapkan data untuk Chart.js
$dataProvinsi = [];
$labels       = [];
while ($row = mysqli_fetch_assoc($result)) {
    if (!in_array($row['periode'], $labels)) {
        $labels[] = $row['periode'];
    }
    $dataProvinsi[$row['provinsi']][$row['periode']] = $row['total_luas'];
}

// Isi nilai kosong dengan 0
foreach ($dataProvinsi as $prov => &$nilai) {
    foreach ($labels as $lbl) {
        if (!isset($nilai[$lbl])) {
            $nilai[$lbl] = 0;
        }
    }
    ksort($nilai);
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    

    <!-- Filter Bar -->
    <div class="card mb-4 p-3 shadow-sm">
        <form method="get" class="row g-3 align-items-end">
            <!-- Komoditas -->
            <div class="col-md-3">
                <label class="form-label">Komoditas</label>
                <select name="komoditas" id="komoditasFilter" class="form-select form-select-sm">
                    <option value="">-- Semua --</option>
                    <?php
                    $q = mysqli_query($koneksi, "SELECT DISTINCT komoditas FROM serangan ORDER BY komoditas");
                    while ($r = mysqli_fetch_assoc($q)) {
                        $sel = ($komoditas == $r['komoditas']) ? 'selected' : '';
                        echo "<option value='{$r['komoditas']}' $sel>{$r['komoditas']}</option>";
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



            <!-- Provinsi -->
            <div class="col-md-4">
                <label class="form-label">Pilih Provinsi (max 5)</label>
                <div class="dropdown w-100">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        Pilih Provinsi
                    </button>
                    <ul class="dropdown-menu p-2" style="max-height: 200px; overflow-y: auto;">
                        <?php foreach ($provList as $p): ?>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="provinsi[]" value="<?= $p ?>" <?= in_array($p, $provinsiDipilih) ? 'checked' : '' ?>>
                                    <label class="form-check-label"><?= $p ?></label>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Tahun Awal -->
            <div class="col-md-2">
                <label class="form-label">Tahun Awal</label>
                <select name="tahun_awal" id="tahunAwal" class="form-select form-select-sm">
                    <option value="">-- Semua --</option>
                    <?php
                    $q = mysqli_query($koneksi, "SELECT DISTINCT tahun FROM serangan ORDER BY tahun ASC");
                    while ($r = mysqli_fetch_assoc($q)) {
                        $sel = ($tahunAwal == $r['tahun']) ? 'selected' : '';
                        echo "<option value='{$r['tahun']}' $sel>{$r['tahun']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Tahun Akhir -->
            <div class="col-md-2">
                <label class="form-label">Tahun Akhir</label>
                <select name="tahun_akhir" id="tahunAkhir" class="form-select form-select-sm">
                    <option value="">-- Semua --</option>
                    <?php
                    $q = mysqli_query($koneksi, "SELECT DISTINCT tahun FROM serangan ORDER BY tahun ASC");
                    while ($r = mysqli_fetch_assoc($q)) {
                        $sel = ($tahunAkhir == $r['tahun']) ? 'selected' : '';
                        echo "<option value='{$r['tahun']}' $sel>{$r['tahun']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Triwulan -->
            <div class="col-md-2">
                <label class="form-label">Triwulan</label>
                <select name="triwulan" id="triwulanFilter" class="form-select form-select-sm">
                    <option value="">-- Semua --</option>
                    <option value="1" <?= ($triwulan == '1') ? 'selected' : '' ?>>Triwulan 1</option>
                    <option value="2" <?= ($triwulan == '2') ? 'selected' : '' ?>>Triwulan 2</option>
                    <option value="3" <?= ($triwulan == '3') ? 'selected' : '' ?>>Triwulan 3</option>
                    <option value="4" <?= ($triwulan == '4') ? 'selected' : '' ?>>Triwulan 4</option>
                </select>
            </div>

            <!-- Tombol -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Terapkan</button>
            </div>
            <div class="col-md-2">
                <a href="<?= basename($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm w-100">Reset</a>
            </div>
        </form>
    </div>

    <!-- Grafik -->
    <h4 class="mb-4">Grafik Garis Total Luas Serangan OPT</h4>
    <canvas id="grafikLine" height="90"></canvas>

    <!-- Tombol Export -->
    <div class="mt-3 text-end">
        <form id="exportForm" action="export_jenisopt.php" method="POST" target="_blank">
            <input type="hidden" name="chartImg" id="chartImg">
            <input type="hidden" name="komoditas" id="komoditas">
            <input type="hidden" name="opt" id="opt">
            <input type="hidden" name="provinsi[]" id="provinsi">
            <input type="hidden" name="tahun_awal" id="tahun_awal">
            <input type="hidden" name="tahun_akhir" id="tahun_akhir">
            <input type="hidden" name="triwulan" id="triwulan">

            <button type="button" class="btn btn-success btn-sm" onclick="exportPDF()">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </button>
        </form>
    </div>
</div>

<!-- Script Export -->
<script>
function exportPDF() {
    const canvas = document.getElementById("grafikLine");
    const chartImg = canvas.toDataURL("image/png");

    document.getElementById("chartImg").value    = chartImg;
    document.getElementById("komoditas").value   = document.getElementById("komoditasFilter").value;
    document.getElementById("opt").value = document.getElementById("optSelect").value;
    document.getElementById("tahun_awal").value  = document.getElementById("tahunAwal").value;
    document.getElementById("tahun_akhir").value = document.getElementById("tahunAkhir").value;
    document.getElementById("triwulan").value    = document.getElementById("triwulanFilter").value;

    // provinsi checkbox
    const provChecked = [...document.querySelectorAll("input[name='provinsi[]']:checked")].map(el => el.value);
    document.getElementById("provinsi").value = provChecked.join(",");

    document.getElementById("exportForm").submit();
}
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafikLine').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels); ?>,
        datasets: [
            <?php
            $colors = ['red', 'blue', 'green', 'orange', 'magenta'];
            $i = 0;
            foreach ($dataProvinsi as $prov => $data) {
                echo "{
                    label: '".addslashes($prov)."',
                    data: ".json_encode(array_values($data)).",
                    borderColor: '{$colors[$i]}',
                    backgroundColor: '{$colors[$i]}33',
                    tension: 0.3,
                    fill: false
                },";
                $i++;
                if ($i >= 5) break; // max 5 provinsi
            }
            ?>
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
<script>
$(function() {
    // saat komoditas berubah → ambil OPT dari get_opt.php
    $('select[name="komoditas"]').on('change', function() {
        let komoditas = $(this).val();
        $.getJSON('get_opt.php', { komoditas: komoditas }, function(data) {
            let $optSelect = $('#optSelect');
            $optSelect.empty().append('<option value="">-- Semua OPT --</option>');
            
            $.each(data, function(i, opt) {
                // kalau ada filter opt yang sudah dipilih sebelumnya, tandai selected
                let selected = (opt === "<?= $_GET['opt'] ?? '' ?>") ? 'selected' : '';
                $optSelect.append('<option value="'+opt+'" '+selected+'>'+opt+'</option>');
            });
        });
    });

    // trigger sekali waktu load, supaya kalau filter komoditas sudah terpilih → OPT otomatis terisi
    $('select[name="komoditas"]').trigger('change');
});
</script>

</body>
</html>
