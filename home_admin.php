<?php
require 'session_admin.php';
include 'connect.php';

$total_serangan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM serangan"))['total'];
$total_provinsi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM provinsi"))['total'];
$total_kabupaten = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kabupaten"))['total'];
$total_komoditas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM komoditas"))['total'];
$total_opt = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM opt"))['total'];
?>

<?php

// Ambil tahun dari GET, default 2024
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : 2024;

// Ambil daftar tahun unik untuk filter
$tahun_query = mysqli_query($koneksi, "SELECT DISTINCT tahun FROM serangan ORDER BY tahun DESC");

// Ambil data grafik berdasarkan tahun
$data_query = mysqli_query($koneksi, "
    SELECT provinsi, 
           SUM(total_luas) AS total,
           SUM(luas_ringan) AS luas_ringan,
           SUM(luas_berat) AS luas_berat
    FROM serangan
    WHERE tahun = $tahun
    GROUP BY provinsi
    ORDER BY total DESC
    LIMIT 10
");


$luas_ringan = [];
$luas_berat = [];

// Ubah while-loop PHP:
while ($row = mysqli_fetch_assoc($data_query)) {
    // Ubah dari KAPITAL → format "Title Case" agar sesuai GeoJSON
    $provinsi = ucwords(strtolower($row['provinsi']));

    $labels[] = $provinsi;
    $values[] = $row['total']; 
    $luas_ringan[] = $row['luas_ringan'];
    $luas_berat[] = $row['luas_berat'];
}

if (!$data_query) {
    die("Query Error: " . mysqli_error($koneksi));
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
      <figure class="text-center">
    <blockquote class="blockquote">
      <p>
        Sistem Informasi Peramalan  <br> Serangan OPT 
        <br>
        <span> <br>
          Balai Besar Perbenihan dan Pelindungan<br>
          Tanaman Perkebunan Medan
        </span>
      </p>
    </blockquote>
  </figure>
<div class="container mt-4">
    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-bug-fill"></i> Data Serangan</h5>
                    <h2><?= $total_serangan ?></h2>
                </div>
            </div>
        </div>

     <div class="col-md-3 position-relative">
    <div class="card shadow text-white bg-success position-relative">
        <div class="card-body">
            <span class="badge bg-light text-dark position-absolute bottom-0 end-0 m-2">
                <?= $total_kabupaten ?> Kabupaten
            </span>
            <h5 class="card-title"><i class="bi bi-bank"></i> Provinsi</h5>
            <h2><?= $total_provinsi ?></h2>
        </div>
    </div>
</div>


        <div class="col-md-3">
            <div class="card shadow text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-tree"></i> Komoditas</h5>
                    <h2><?= $total_komoditas ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-briefcase"></i> Jenis OPT</h5>
                    <h2><?= $total_opt ?></h2>
                </div>
            </div>
        </div>
<!-- Section: Peta Interaktif -->

    <h4 class="mb-3">Peta Serangan OPT per Provinsi</h4>

<!-- Dropdown Tahun (otomatis dari database) -->
<div class="mb-3 d-flex gap-3 flex-wrap">

  <!-- Tahun -->
  <div>
    <label for="tahunSelect" class="form-label">Tahun:</label>
    <select id="tahunSelect" class="form-select" style="width: 150px;">
      <option value="">Semua Tahun</option>
      <?php mysqli_data_seek($tahun_query, 0); while ($t = mysqli_fetch_assoc($tahun_query)) { ?>
        <option value="<?= $t['tahun'] ?>"><?= $t['tahun'] ?></option>
      <?php } ?>
    </select>
  </div>

  <!-- Jenis OPT (dipersempit) -->
  <div>
    <label for="optSelect" class="form-label">Jenis OPT:</label>
    <select id="optSelect" class="form-select" style="width:500px;">
      <option value="">Semua</option>
      <?php
        $q_opt = mysqli_query($koneksi, "SELECT DISTINCT nama_opt FROM opt ORDER BY nama_opt ASC");
        while ($opt = mysqli_fetch_assoc($q_opt)) {
          echo "<option value='{$opt['nama_opt']}'>{$opt['nama_opt']}</option>";
        }
      ?>
    </select>
  </div>

  <!-- Komoditas (diperlebar) -->
  <div>
    <label for="komoditasSelect" class="form-label">Komoditas:</label>
    <select id="komoditasSelect" class="form-select" style="width: 300px; font-size: 1rem; font-weight: 500;">
      <option value="">Semua</option>
      <?php
        $q_komoditas = mysqli_query($koneksi, "SELECT DISTINCT nama_komoditas FROM komoditas ORDER BY nama_komoditas ASC");
        while ($kom = mysqli_fetch_assoc($q_komoditas)) {
          echo "<option value='{$kom['nama_komoditas']}'>{$kom['nama_komoditas']}</option>";
        }
      ?>
    </select>
  </div>

  <!-- Triwulan (hidden) -->
<div id="triwulanFilter" style="display: none;">
  <label for="triwulanSelect" class="form-label">Triwulan:</label>
  <select id="triwulanSelect" class="form-select" style="width: 180px;">
    <option value="">Semua</option>
    <option value="1">Triwulan 1 (Jan-Mar)</option>
    <option value="2">Triwulan 2 (Apr-Jun)</option>
    <option value="3">Triwulan 3 (Jul-Sep)</option>
    <option value="4">Triwulan 4 (Okt-Des)</option>
  </select>
</div>


</div>



    <!-- Map -->
    <div id="map" style="height: 400px; border-radius: 10px;"></div>

    <!-- Legend -->
<div id="legend" class="mt-3 p-2" style="background-color: #f8f9fa; border-radius: 5px; width: fit-content;">
  <strong>Legenda Warna (Total Serangan):</strong><br>
  <i style="background: #b30000; width: 18px; height: 18px; display: inline-block;"></i> Sangat Tinggi (>1jt Ha)<br>
  <i style="background: #e60000; width: 18px; height: 18px; display: inline-block;"></i> Tinggi (>500rb Ha)<br>
  <i style="background: #ffcc00; width: 18px; height: 18px; display: inline-block;"></i> Sedang (>300rb Ha)<br>
  <i style="background: #99ff99; width: 18px; height: 18px; display: inline-block;"></i> Rendah (>100rb Ha)<br>
  <i style="background: #4caf50; width: 18px; height: 18px; display: inline-block;"></i> Sangat Rendah (≤100rb Ha)
</div>



</div>
    <!-- Panduan Pelayanan Sistem -->
<div class="panduan-box mt-4 p-3">
    <h5><i class="bi bi-info-circle-fill"></i> Panduan Pelayanan Sistem</h5>
    <ul>
        <li><strong>Pilih Tahun</strong> untuk menyesuaikan data serangan OPT per provinsi.</li>
        <li><strong>Peta Warna</strong> menunjukkan tingkat serangan:
            <ul>
                <li>Merah = Tinggi</li>
                <li>Kuning = Sedang</li>
                <li>Hijau = Rendah</li>
            </ul>
        </li>
        <li>Data diperbarui secara berkala berdasarkan laporan dari provinsi.</li>
        <li>Gunakan menu di samping untuk akses data serangan, jenis OPT, dan komoditas.</li>
        <li>Hubungi admin sistem untuk data terbaru atau pelaporan kesalahan.</li>
    </ul>
</div>
<br>


</form>

<label for="tahunGrafik">Pilih Tahun (Grafik):</label>
<select id="tahunGrafik" class="form-select" style="width: auto; display: inline-block;">
  <?php mysqli_data_seek($tahun_query, 0); while ($t = mysqli_fetch_assoc($tahun_query)) { ?>
    <option value="<?= $t['tahun'] ?>" <?= ($t['tahun'] == $tahun) ? 'selected' : '' ?>>
      <?= $t['tahun'] ?>
    </option>
  <?php } ?>
</select>

<h5 class="mt-3"><i class="bi bi-bar-chart-fill"></i> 
  Total Serangan per Provinsi - Tahun <span id="tahunLabel"><?= $tahun ?></span>
</h5>

<canvas id="grafikTotalLuas" height="120"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafikTotalLuas').getContext('2d');

let chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [
      {
        label: 'Luas Ringan',
        data: <?= json_encode($luas_ringan) ?>,
        backgroundColor: 'rgba(255, 206, 86, 0.7)',
        stack: 'serangan'
      },
      {
        label: 'Luas Berat',
        data: <?= json_encode($luas_berat) ?>,
        backgroundColor: 'rgba(255, 99, 132, 0.7)',
        stack: 'serangan'
      }
    ]
  },
  options: {
    indexAxis: 'y',
    responsive: true,
    plugins: {
      legend: { position: 'top' },
      tooltip: { mode: 'index', intersect: false }
    },
    scales: {
      x: { stacked: true },
      y: { stacked: true }
    }
  }
});

// Fungsi update chart
function updateChart(data) {
  chart.data.labels = data.map(d => d.provinsi);
  chart.data.datasets[0].data = data.map(d => d.luas_ringan);
  chart.data.datasets[1].data = data.map(d => d.luas_berat);
  chart.update();
}

// Saat dropdown tahun grafik diubah
document.getElementById('tahunGrafik').addEventListener('change', function () {
  const tahun = this.value;
  fetch('get_grafik_data.php?tahun=' + tahun)
    .then(res => res.json())
    .then(data => {
      updateChart(data);
      document.getElementById('tahunLabel').innerText = tahun;
    });
});
</script>



 <script>
var map = L.map('map').setView([-2.5, 117], 5);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let geojsonLayer;

// Skala warna lebih besar sesuai data Ha
function getColor(value) {
  return value > 1000000 ? '#b30000' : // sangat tinggi
         value > 500000  ? '#e60000' : // tinggi
         value > 300000  ? '#ffcc00' : // sedang
         value > 100000  ? '#99ff99' : // rendah
         value > 0       ? '#4caf50' : // sangat rendah
                           '#d9d9d9'; // abu-abu untuk 0 / tidak ada data
}

// Fungsi untuk load dan style ulang peta berdasarkan tahun
// Fungsi untuk load dan style ulang peta berdasarkan tahun
function loadMap() {
  const tahun = document.getElementById('tahunSelect').value;
  const opt = document.getElementById('optSelect').value;
  const komoditas = document.getElementById('komoditasSelect').value;

  if (geojsonLayer) map.removeLayer(geojsonLayer);

  const params = new URLSearchParams({ tahun, opt, komoditas });

  fetch('get_geojson.php?' + params.toString())
    .then(res => res.json())
    .then(geojson => {
      // Ambil nilai total serangan semua provinsi
      let values = geojson.features.map(f => f.properties.total_serangan || 0);
      let maxVal = Math.max(...values);

      // Fungsi warna sesuai persentase
      function getColorByPercent(value) {
        if (value === 0) return '#ffffff'; // putih untuk 0%
        let percent = (value / maxVal) * 100;
        if (percent <= 20) return '#4caf50';   // hijau (aman)
        if (percent <= 60) return '#ffcc00';   // kuning (rendah)
        if (percent <= 80) return '#ff0000';   // merah (menengah)
        return '#800000';                      // merah tua (sangat tinggi)
      }

      // Tambah layer geojson ke peta
      geojsonLayer = L.geoJSON(geojson, {
        style: feature => {
          const val = feature.properties.total_serangan || 0;
          return {
            fillColor: getColorByPercent(val),
            fillOpacity: 0.7,
            color: "white",
            weight: 1
          };
        },
        onEachFeature: (feature, layer) => {
          const p = feature.properties;
          let percent = maxVal > 0 ? ((p.total_serangan / maxVal) * 100).toFixed(1) : 0;
          layer.bindPopup(`
            <strong>${p.Propinsi}</strong><br>
            <b>Total Serangan:</b> ${p.total_serangan.toLocaleString()} Ha<br>
            <b>Persentase:</b> ${percent}%
          `);
        }
      }).addTo(map);

      // Update legenda
      const legendDiv = document.getElementById('legend');
      legendDiv.innerHTML = `
        <strong>Legenda Warna (Total Serangan, dibandingkan tertinggi):</strong><br>
        <i style="background:#ffffff; width:18px; height:18px; display:inline-block; border:1px solid #999;"></i> 0% (Tidak Ada)<br>
        <i style="background:#4caf50; width:18px; height:18px; display:inline-block;"></i> 1–20% (Aman)<br>
        <i style="background:#ffcc00; width:18px; height:18px; display:inline-block;"></i> 21–60% (Rendah)<br>
        <i style="background:#ff0000; width:18px; height:18px; display:inline-block;"></i> 61–80% (Menengah)<br>
        <i style="background:#800000; width:18px; height:18px; display:inline-block;"></i> 81–100% (Sangat Tinggi)<br>
        <br>
        <small><b>Provinsi tertinggi:</b> ${maxVal.toLocaleString()} Ha = 100%</small>
      `;
    });
}



// Jalankan saat load pertama
loadMap();

// Event listener untuk semua dropdown
['tahunSelect','optSelect','komoditasSelect','triwulanSelect'].forEach(id => {
  document.getElementById(id).addEventListener('change', loadMap);
});

</script>


<script>
  const urlParams = new URLSearchParams(window.location.search);
  const status = urlParams.get('status');

  if (status === 'success' || status === 'error') {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    const modalHeader = document.getElementById('modalHeader');
    const modalBody = document.getElementById('modalBody');

    if (status === 'success') {
      modalHeader.classList.remove('bg-danger');
      modalHeader.classList.add('bg-success');
      modalBody.innerText = '✅ Data berhasil diimpor!';
    } else {
      modalHeader.classList.remove('bg-success');
      modalHeader.classList.add('bg-danger');
      modalBody.innerText = '❌ Gagal mengimpor data. File kosong atau tidak valid.';
    }

    modal.show();
  }
</script>



</body>
</html>
