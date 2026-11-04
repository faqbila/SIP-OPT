<?php
require 'session_user.php';
include 'connect.php';

$where = [];

if (!empty($_GET['provinsi'])) {
    $provinsi = mysqli_real_escape_string($koneksi, $_GET['provinsi']);
    $where[] = "provinsi = '$provinsi'";
}

if (!empty($_GET['tahun_awal']) && !empty($_GET['tahun_akhir'])) {
    $tahun_awal = (int)$_GET['tahun_awal'];
    $tahun_akhir = (int)$_GET['tahun_akhir'];
    $where[] = "tahun BETWEEN $tahun_awal AND $tahun_akhir";
} elseif (!empty($_GET['tahun_awal'])) {
    $tahun_awal = (int)$_GET['tahun_awal'];
    $where[] = "tahun >= $tahun_awal";
} elseif (!empty($_GET['tahun_akhir'])) {
    $tahun_akhir = (int)$_GET['tahun_akhir'];
    $where[] = "tahun <= $tahun_akhir";
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

      <div class="col-md-2">
        <label class="form-label">Provinsi</label>
        <select name="provinsi" class="form-select form-select-sm" required>
          <option value="">-- Pilih Provinsi --</option>
          <?php
          $provQuery = mysqli_query($koneksi, "SELECT DISTINCT provinsi FROM serangan ORDER BY provinsi");
          while ($prov = mysqli_fetch_assoc($provQuery)) {
            $selected = ($_GET['provinsi'] ?? '') == $prov['provinsi'] ? 'selected' : '';
            echo "<option value='{$prov['provinsi']}' $selected>{$prov['provinsi']}</option>";
          }
          ?>
        </select>
      </div>

     <div class="col-md-2">
  <label class="form-label">Tahun Awal</label>
  <select name="tahun_awal" class="form-select form-select-sm">
    <option value="">-- Pilih --</option>
    <?php
    $tahunQuery = mysqli_query($koneksi, "SELECT DISTINCT tahun FROM serangan ORDER BY tahun DESC");
    while ($th = mysqli_fetch_assoc($tahunQuery)) {
      $selected = ($_GET['tahun_awal'] ?? '') == $th['tahun'] ? 'selected' : '';
      echo "<option value='{$th['tahun']}' $selected>{$th['tahun']}</option>";
    }
    ?>
  </select>
</div>

<div class="col-md-2">
  <label class="form-label">Tahun Akhir</label>
  <select name="tahun_akhir" class="form-select form-select-sm">
    <option value="">-- Pilih --</option>
    <?php
    $tahunQuery2 = mysqli_query($koneksi, "SELECT DISTINCT tahun FROM serangan ORDER BY tahun DESC");
    while ($th2 = mysqli_fetch_assoc($tahunQuery2)) {
      $selected = ($_GET['tahun_akhir'] ?? '') == $th2['tahun'] ? 'selected' : '';
      echo "<option value='{$th2['tahun']}' $selected>{$th2['tahun']}</option>";
    }
    ?>
  </select>
</div>


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

      <div class="col-md-2">
        <label class="form-label">Komoditas</label>
        <select name="komoditas" class="form-select form-select-sm">
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

<div class="col-md-2">
  <label class="form-label">OPT</label>
  <select name="opt" id="optSelect" class="form-select form-select-sm">
    <option value="">-- Semua OPT --</option>
  </select>
</div>


      <div class="col-md-2 d-flex flex-column align-items-stretch">
        <button type="submit" class="btn btn-primary btn-sm mb-2 w-100">
          <i class="bi bi-filter"></i> Filter
        </button>
        <a href="serangan.php" class="btn btn-secondary btn-sm w-100">
          <i class="bi bi-x-circle"></i> Reset
        </a>
      </div>

    </div>
  </form>

  <?php if (!empty($_GET['provinsi'])): ?>
    <?php
    $totalQuery = "SELECT 
        SUM(luas_ringan) AS total_ringan, 
        SUM(luas_berat) AS total_berat, 
        SUM(total_luas) AS total_all
        FROM serangan $whereSQL";
    $totalResult = mysqli_query($koneksi, $totalQuery);
    $totals = mysqli_fetch_assoc($totalResult);
    ?>

    <!-- Ringkasan -->
    <div class="table-responsive mb-3">
      <table class="table table-bordered table-sm text-center" style="max-width: 800px; margin: 0 auto;">
        <thead class="table-secondary">
          <tr>
            <th>Provinsi</th>
            <th>Tahun Awal</th>
            <th>Tahun Akhir</th>
            <th>Triwulan</th>
            <th>Komoditas</th>
            <th>OPT</th>
            <th>Ringan (Ha)</th>
            <th>Berat (Ha)</th>
            <th>Total (Ha)</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?= htmlspecialchars($_GET['provinsi'] ?? '-') ?></td>
            <td><?= htmlspecialchars($_GET['tahun_awal'] ?? '-') ?></td>
            <td><?= htmlspecialchars($_GET['tahun_akhir'] ?? '-') ?></td>
            <td><?= htmlspecialchars($_GET['triwulan'] ?? '-') ?></td>
            <td><?= htmlspecialchars($_GET['komoditas'] ?? '-') ?></td>
            <td><?= htmlspecialchars($_GET['opt'] ?? '-') ?></td>
            <td><?= number_format($totals['total_ringan'] ?? 0, 2) ?></td>
            <td><?= number_format($totals['total_berat'] ?? 0, 2) ?></td>
            <td><?= number_format($totals['total_all'] ?? 0, 2) ?> </td> 
            <td> <a href="grafik_serangan.php?provinsi=<?= urlencode($_GET['provinsi'] ?? '') ?>
&tahun_awal=<?= urlencode($_GET['tahun_awal'] ?? '') ?>
&tahun_akhir=<?= urlencode($_GET['tahun_akhir'] ?? '') ?>
&triwulan=<?= urlencode($_GET['triwulan'] ?? '') ?>
&komoditas=<?= urlencode($_GET['komoditas'] ?? '') ?>
&opt=<?= urlencode($_GET['opt'] ?? '') ?>"
class="btn btn-sm btn-primary">
    Lihat Grafik
</a>
</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Tabel Detail -->
    <p>Data Serangan Provinsi: <?= htmlspecialchars($_GET['provinsi']) ?></p>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Tahun</th>
          <th>Triwulan</th>
          <th>Kabupaten</th>
          <th>Komoditas</th>
          <th>OPT</th>
          <th>Ringan (Ha)</th>
          <th>Berat (Ha)</th>
          <th>Total (Ha)</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>
            <td>{$no}</td>
            <td>{$row['tahun']}</td>
            <td>{$row['triwulan']}</td>
            <td>{$row['kabupaten']}</td>
            <td>{$row['komoditas']}</td>
            <td>{$row['opt']}</td>
            <td>{$row['luas_ringan']}</td>
            <td>{$row['luas_berat']}</td>
            <td>{$row['total_luas']}</td>
          </tr>";
          $no++;
        }
        ?>
      </tbody>
    </table>

  <?php else: ?>
    <div class="alert alert-info mt-3">Silakan masukkan filter terlebih dahulu untuk menampilkan data.</div>
  <?php endif; ?>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
