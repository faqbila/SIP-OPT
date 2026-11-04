<?php
require 'session_user.php';
include 'connect.php';

// Ambil daftar provinsi
$provList = [];
$provQuery = mysqli_query($koneksi, "SELECT DISTINCT provinsi FROM serangan ORDER BY provinsi ASC");
while ($p = mysqli_fetch_assoc($provQuery)) {
    $provList[] = $p['provinsi'];
}

// Ambil daftar OPT untuk pilihan
$optList = [];
$optQuery = mysqli_query($koneksi, "SELECT DISTINCT opt FROM serangan ORDER BY opt ASC");
while ($o = mysqli_fetch_assoc($optQuery)) {
    $optList[] = $o['opt'];
}

// Ambil daftar tahun unik
$tahunList = [];
$tahunQuery = mysqli_query($koneksi, "SELECT DISTINCT tahun FROM serangan ORDER BY tahun ASC");
while ($t = mysqli_fetch_assoc($tahunQuery)) {
    $tahunList[] = $t['tahun'];
}
// Ambil daftar triwulan unik
$triwulanList = [];
$triwulanQuery = mysqli_query($koneksi, "SELECT DISTINCT triwulan FROM serangan ORDER BY triwulan ASC");
while ($tr = mysqli_fetch_assoc($triwulanQuery)) {
    $triwulanList[] = $tr['triwulan'];
}




// Ambil filter dari request
$komoditas = $_GET['komoditas'] ?? '';
$provinsi  = $_GET['provinsi'] ?? '';
$tahun_awal  = $_GET['tahun_awal'] ?? '';
$tahun_akhir = $_GET['tahun_akhir'] ?? '';
$triwulan = $_GET['triwulan'] ?? '';
$optPilihan = $_GET['opt'] ?? []; // array

// Bangun klausa WHERE
$where = [];
if (!empty($komoditas)) {
    $komo = mysqli_real_escape_string($koneksi, $komoditas);
    $where[] = "komoditas = '$komo'";
}
if (!empty($provinsi)) {
    $prov = mysqli_real_escape_string($koneksi, $provinsi);
    $where[] = "provinsi = '$prov'";
}
if (!empty($optPilihan)) {
    $optIn = array_map(function($o) use ($koneksi) {
        return "'" . mysqli_real_escape_string($koneksi, $o) . "'";
    }, $optPilihan);
    $where[] = "opt IN (" . implode(",", $optIn) . ")";
}
if (!empty($tahun_awal)) {
    $tahun_awal = (int)$tahun_awal;
    $where[] = "tahun >= $tahun_awal";
}
if (!empty($tahun_akhir)) {
    $tahun_akhir = (int)$tahun_akhir;
    $where[] = "tahun <= $tahun_akhir";
}
if (!empty($triwulan)) {
    $triwulan = mysqli_real_escape_string($koneksi, $triwulan);
    $where[] = "triwulan = '$triwulan'";
}

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// Ambil data OPT per tahun
$dataOPT = [];
$labels  = [];
$sql = "
    SELECT tahun, triwulan, opt, SUM(total_luas) AS total_luas
    FROM serangan
    $whereSQL
    GROUP BY tahun, triwulan, opt
    ORDER BY tahun ASC, triwulan ASC
";

$result = mysqli_query($koneksi, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $label = $row['tahun'] . ' - T' . $row['triwulan']; // contoh: 2024 - T1
    if (!in_array($label, $labels)) {
        $labels[] = $label;
    }
    $dataOPT[$row['opt']][$label] = $row['total_luas'];
}
foreach ($dataOPT as $opt => &$nilai) {
    foreach ($labels as $lbl) {
        if (!isset($nilai[$lbl])) {
            $nilai[$lbl] = 0;
        }
    }
    ksort($nilai);
}

?>
<?php
// === Data untuk Grafik Batang (OPT vs Total Luas per Triwulan) ===
$dataBar = [];
$labelsBar = [];
$sqlBar = "
    SELECT opt, triwulan, SUM(total_luas) AS total_luas
    FROM serangan
    $whereSQL
    GROUP BY opt, triwulan
    ORDER BY opt ASC, triwulan ASC
";
$resBar = mysqli_query($koneksi, $sqlBar);
while ($row = mysqli_fetch_assoc($resBar)) {
    if (!in_array($row['opt'], $labelsBar)) {
        $labelsBar[] = $row['opt'];
    }
    $dataBar[$row['triwulan']][$row['opt']] = $row['total_luas'];
}
// lengkapi nilai kosong jadi 0
foreach ($dataBar as $tri => &$vals) {
    foreach ($labelsBar as $opt) {
        if (!isset($vals[$opt])) {
            $vals[$opt] = 0;
        }
    }
    ksort($vals);
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
    <link rel="stylesheet" href="tampilan.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

<div class="content">
   

   <div class="card mb-4 p-3 shadow-sm  ">
      <form method="get" class="row g-3 align-items-end">

    <!-- Komoditas -->
    <div class="col-md-3">
        <label class="form-label">Komoditas</label>
        <select name="komoditas" class="form-select form-select-sm">
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

    <!-- Provinsi -->
    <div class="col-md-3">
        <label class="form-label">Provinsi</label>
        <select name="provinsi" class="form-select form-select-sm">
            <option value="">-- Semua --</option>
            <?php foreach ($provList as $p) {
                $sel = ($provinsi == $p) ? 'selected' : '';
                echo "<option value='$p' $sel>$p</option>";
            } ?>
        </select>
    </div>

<!-- Tahun Awal -->
<div class="col-md-2">
    <label class="form-label">Tahun Awal</label>
    <select name="tahun_awal" class="form-select form-select-sm">
        <option value="">-- Semua --</option>
        <?php foreach ($tahunList as $t) {
            $sel = ($tahun_awal == $t) ? 'selected' : '';
            echo "<option value=\"$t\" $sel>$t</option>";
        } ?>
    </select>
</div>

<!-- Tahun Akhir -->
<div class="col-md-2">
    <label class="form-label">Tahun Akhir</label>
    <select name="tahun_akhir" class="form-select form-select-sm">
        <option value="">-- Semua --</option>
        <?php foreach ($tahunList as $t) {
            $sel = ($tahun_akhir == $t) ? 'selected' : '';
            echo "<option value=\"$t\" $sel>$t</option>";
        } ?>
    </select>
</div>

<!-- Triwulan -->
<div class="col-md-2">
    <label class="form-label">Triwulan</label>
    <select name="triwulan" class="form-select form-select-sm">
        <option value="">-- Semua --</option>
        <?php foreach ($triwulanList as $tr) {
            $sel = ($triwulan == $tr) ? 'selected' : '';
            echo "<option value=\"$tr\" $sel>Triwulan $tr</option>";
        } ?>
    </select>
</div>

    <!-- OPT -->
<div class="col-md-4">
    <label class="form-label">Jenis OPT</label>
    <select id="optSelect" name="opt[]" class="form-select form-select-sm" multiple>
        <?php foreach ($optList as $o) {
            $sel = (in_array($o, $optPilihan)) ? 'selected' : '';
            echo "<option value=\"$o\" $sel>$o</option>";
        } ?>
    </select>
</div>

    <!-- Tombol -->
<div class="col-md-2">
    <button type="submit" class="btn btn-primary btn-sm w-100">Terapkan</button>
</div>
<div class="col-md-2">
    <a href="<?php echo basename($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm w-100">Reset</a>
</div>

</form>

    </div>

    <!-- Grafik -->
    <h4 class="mb-4">Grafik Garis Total Luas Serangan OPT</h4>
    <canvas id="grafikLine" height="120"></canvas>
    <!-- Tombol Export -->
<!-- Tombol Export di kanan, warna hijau -->
<!-- Tombol Export di kanan, warna hijau -->
<div class="text-end mb-3">
    <button id="btnExport" class="btn btn-success">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </button>
</div>

<h4 class="mt-5 mb-4">Grafik Batang Total Luas per OPT</h4>
<canvas id="grafikBar" height="120"></canvas>


<form id="exportForm" method="POST" action="export_grafikopt.php" target="_blank">
    <input type="hidden" name="barImg" id="barImg">
    <input type="hidden" name="komoditas" id="komoditasExport">
    <input type="hidden" name="provinsi" id="provinsiExport">
    <input type="hidden" name="tahun_awal" id="tahunAwalExport">
    <input type="hidden" name="tahun_akhir" id="tahunAkhirExport">
    <input type="hidden" name="triwulan" id="triwulanExport">
    <input type="hidden" name="opt" id="optExport">
</form>
<script>
const ctxBar = document.getElementById('grafikBar').getContext('2d');

const dataBar = {
    labels: <?php echo json_encode($labelsBar); ?>,
    datasets: [
        <?php
        $colors = ['#e74c3c','#3498db','#2ecc71','#f1c40f']; // warna triwulan
        $i=0;
        foreach ($dataBar as $tri => $vals) {
            echo "{
                label: 'Triwulan $tri',
                data: ".json_encode(array_values($vals)).",
                backgroundColor: '{$colors[$i]}'
            },";
            $i++;
        }
        ?>
    ]
};

// === Gaya 1: STACKED BAR ===
const stackedOptions = {
    responsive: true,
    plugins: { legend: { position: 'bottom' } },
    scales: {
        x: { stacked: true },
        y: { beginAtZero: true, stacked: true }
    }
};

// === Gaya 2: GROUPED BAR (berdampingan) ===
const groupedOptions = {
    responsive: true,
    plugins: { legend: { position: 'bottom' } },
    scales: {
        x: { stacked: false },
        y: { beginAtZero: true, stacked: false }
    }
};

// Ubah di sini sesuai kebutuhan (stackedOptions atau groupedOptions)
new Chart(ctxBar, {
    type: 'bar',
    data: dataBar,
    options: groupedOptions   // <-- ganti ke stackedOptions bila mau versi stacked
});
</script>

<script>
// Klik tombol Export
$('#btnExport').on('click', function() {
    // Ambil gambar canvas yang benar
    var canvas = document.getElementById('grafikLine'); 
    var imgData = canvas.toDataURL('image/png');

    // Isi form dengan filter
    $('#barImg').val(imgData);
    $('#komoditasExport').val($('select[name="komoditas"]').val());
    $('#provinsiExport').val($('select[name="provinsi"]').val());
    $('#tahunAwalExport').val($('select[name="tahun_awal"]').val());
    $('#tahunAkhirExport').val($('select[name="tahun_akhir"]').val());
    $('#triwulanExport').val($('select[name="triwulan"]').val());
    $('#optExport').val($('#optSelect').val());

    // Submit form
    $('#exportForm').submit();
});
</script>


</div>

<script>
const ctx = document.getElementById('grafikLine').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [
            <?php
            $colors = ['red', 'blue', 'green', 'orange', 'purple'];
            $i = 0;
            foreach ($dataOPT as $optName => $data) {
                echo "{
                    label: '".addslashes($optName)."',
                    data: ".json_encode(array_values($data)).",
                    borderColor: '{$colors[$i]}',
                    backgroundColor: '{$colors[$i]}33',
                    tension: 0.3,
                    fill: false
                },";
                $i++;
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
$(document).ready(function() {
    $('#optSelect').select2({
        placeholder: "Pilih jenis OPT",
        allowClear: true,
        width: '100%'
    });
});
// Saat komoditas berubah, update dropdown OPT
$('select[name="komoditas"]').on('change', function() {
    var komoditas = $(this).val();
    $.ajax({
        url: 'get_opt_by_komoditas.php',
        type: 'GET',
        data: { komoditas: komoditas },
        success: function(res) {
            let data = JSON.parse(res);
            let $optSelect = $('#optSelect');
            $optSelect.empty(); // kosongkan dulu
            data.forEach(function(opt) {
                $optSelect.append('<option value="'+opt+'">'+opt+'</option>');
            });
            // Refresh select2 agar langsung terlihat
            $optSelect.trigger('change');
        }
    });
});

</script>

</body>
</html>
