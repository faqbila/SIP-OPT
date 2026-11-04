<?php
require 'session_user.php';
include 'connect.php';
// =============================
// Filtering Data
// =============================
$where = [];

if (!empty($_GET['provinsi'])) {
    $provinsi = mysqli_real_escape_string($koneksi, $_GET['provinsi']);
    $where[] = "provinsi = '$provinsi'";
}
if (!empty($_GET['tahun_awal']) && !empty($_GET['tahun_akhir'])) {
    $tahun_awal = (int)$_GET['tahun_awal'];
    $tahun_akhir = (int)$_GET['tahun_akhir'];
    $where[] = "tahun BETWEEN $tahun_awal AND $tahun_akhir";
} elseif (!empty($_GET['tahun'])) {
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
if (!empty($_GET['opt'])) {
    $opt = mysqli_real_escape_string($koneksi, $_GET['opt']);
    $where[] = "opt LIKE '%$opt%'";
}

$whereSQL = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$query = "SELECT * FROM serangan $whereSQL ORDER BY tahun DESC, triwulan DESC";
$result = mysqli_query($koneksi, $query);

// =============================
// Data untuk Grafik Batang
// =============================
$labels        = [];
$luas_ringan   = [];
$luas_berat    = [];
$total_luas    = [];
$tahun_data    = [];
$triwulan_data = [];
$kabupaten_data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[]        = $row['kabupaten'];
    $luas_ringan[]   = (float)$row['luas_ringan'];
    $luas_berat[]    = (float)$row['luas_berat'];
    $total_luas[]    = (float)$row['total_luas'];
    $tahun_data[]    = $row['tahun'];
    $triwulan_data[] = $row['triwulan'];
    $kabupaten_data[] = $row['kabupaten'];
}

// =============================
// Data untuk Grafik Garis
// =============================
$query_line = "
    SELECT tahun, triwulan, kabupaten, SUM(total_luas) AS total_luas
    FROM serangan
    $whereSQL
    GROUP BY tahun, triwulan, kabupaten
    ORDER BY tahun ASC, triwulan ASC
";
$result_line = mysqli_query($koneksi, $query_line);

$line_labels   = [];
$datasets_line = [];
$data_kabupaten = [];

while ($row = mysqli_fetch_assoc($result_line)) {
    $label_x = $row['tahun'] . ' - T' . $row['triwulan'];

    if (!in_array($label_x, $line_labels)) {
        $line_labels[] = $label_x;
    }

    $data_kabupaten[$row['kabupaten']][$label_x] = (float)$row['total_luas'];
}

foreach ($data_kabupaten as $kab => $data) {
    $values = [];
    foreach ($line_labels as $lbl) {
        $values[] = isset($data[$lbl]) ? $data[$lbl] : 0;
    }
    $datasets_line[] = [
        'label'           => $kab,
        'data'            => $values,
        'borderColor'     => 'rgba(' . rand(0,255) . ',' . rand(0,255) . ',' . rand(0,255) . ', 1)',
        'backgroundColor' => 'transparent',
        'tension'         => 0.3
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <link rel="shortcut icon" href="logo2.png" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grafik Serangan OPT - BBPPTP Medan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
  <div class="container-fluid">
    <h4 class="text-center my-4">
      Grafik Serangan OPT  
      <?php 
        echo !empty($_GET['opt']) ? htmlspecialchars($_GET['opt']) : "Semua OPT";
        echo " - ";
        if (!empty($_GET['tahun_awal']) && !empty($_GET['tahun_akhir'])) {
            echo "Tahun " . htmlspecialchars($_GET['tahun_awal']) . " - " . htmlspecialchars($_GET['tahun_akhir']);
        } elseif (!empty($_GET['tahun'])) {
            echo "Tahun " . htmlspecialchars($_GET['tahun']);
        } else {
            echo "Semua Tahun";
        }
        echo " - ";
        echo !empty($_GET['komoditas']) ? "Komoditas " . htmlspecialchars($_GET['komoditas']) : "Semua Komoditas";
      ?>
    </h4>

    <!-- Grafik Batang -->
    <canvas id="grafikBar" height="120"></canvas>

    <!-- Grafik Garis -->
    <h5 class="mt-5 text-center">Tren Total Luas per Kabupaten</h5>
    <canvas id="grafikLine" height="120"></canvas>

    <!-- Tombol -->
    <div class="d-flex justify-content-between align-items-center mt-4">
      <a href="serangan.php" class="btn btn-primary btn-lg px-4">
        Kembali
      </a>
      <button onclick="exportPDF()" class="btn btn-success btn-lg px-4">
        Export ke PDF
      </button>
    </div>
  </div>
</div>

<!-- Script Export -->
<script>
function exportPDF() {
  const barCanvas  = document.getElementById('grafikBar');
  const lineCanvas = document.getElementById('grafikLine');

  const barImg  = barCanvas.toDataURL('image/png');
  const lineImg = lineCanvas.toDataURL('image/png');

  const form = document.createElement('form');
  form.method = 'POST';
  form.action = 'export_kabupaten.php';
  form.target = '_blank';

  // kirim gambar grafik
  const input1 = document.createElement('input');
  input1.type = 'hidden'; input1.name = 'barImg'; input1.value = barImg;
  form.appendChild(input1);

  const input2 = document.createElement('input');
  input2.type = 'hidden'; input2.name = 'lineImg'; input2.value = lineImg;
  form.appendChild(input2);

  // kirim filter
  <?php foreach ($_GET as $key => $val): ?>
    const inp_<?= $key ?> = document.createElement('input');
    inp_<?= $key ?>.type = 'hidden';
    inp_<?= $key ?>.name = '<?= $key ?>';
    inp_<?= $key ?>.value = '<?= htmlspecialchars($val, ENT_QUOTES) ?>';
    form.appendChild(inp_<?= $key ?>);
  <?php endforeach; ?>

  document.body.appendChild(form);
  form.submit();
}
</script>

<!-- Script Chart -->
<script>
  const labels        = <?= json_encode($labels) ?>;
  const luasRingan    = <?= json_encode($luas_ringan) ?>;
  const luasBerat     = <?= json_encode($luas_berat) ?>;
  const totalLuas     = <?= json_encode($total_luas) ?>;
  const tahunData     = <?= json_encode($tahun_data) ?>;
  const triwulanData  = <?= json_encode($triwulan_data) ?>;
  const kabupatenData = <?= json_encode($kabupaten_data) ?>;
new Chart(document.getElementById('grafikBar'), {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [
      {
        label: 'Luas Ringan (Ha)',
        data: luasRingan,
        backgroundColor: 'rgba(54, 162, 235, 0.7)',
        stack: 'Stack 0'
      },
      {
        label: 'Luas Berat (Ha)',
        data: luasBerat,
        backgroundColor: 'rgba(255, 99, 132, 0.7)',
        stack: 'Stack 0'
      }
      // Jika total = ringan + berat, tidak perlu dataset total
    ]
  },
  options: {
    indexAxis: 'y',
    responsive: true,
    plugins: {
      tooltip: { mode: 'index', intersect: false }
    },
    scales: {
      x: { stacked: true, title: { display: true, text: 'Luas Serangan (Ha)' } },
      y: { stacked: true, title: { display: true, text: 'Kabupaten' } }
    }
  }
});



  const lineLabels   = <?= json_encode($line_labels) ?>;
  const lineDatasets = <?= json_encode($datasets_line) ?>;

  new Chart(document.getElementById('grafikLine'), {
    type: 'line',
    data: { labels: lineLabels, datasets: lineDatasets },
    options: { responsive: true }
  });
</script>
</body>
</html>
