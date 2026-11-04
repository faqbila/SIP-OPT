<?php
require 'session_admin.php';
include 'connect.php';
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
   

   <form method="get" class="mb-3">
  <label for="tahun">Filter Tahun:</label>
  <select name="tahun" id="tahun" class="form-select" style="width: 200px; display:inline;">
    <option value="">Semua</option>
    <?php
    include 'connect.php';

    // Ambil tahun unik dari tabel serangan, urutkan terbaru
    $resultTahun = mysqli_query($koneksi, "SELECT DISTINCT tahun FROM serangan ORDER BY tahun DESC");
    while ($rowTahun = mysqli_fetch_assoc($resultTahun)) {
        $selected = (isset($_GET['tahun']) && $_GET['tahun'] == $rowTahun['tahun']) ? 'selected' : '';
        echo "<option value='{$rowTahun['tahun']}' $selected>{$rowTahun['tahun']}</option>";
    }
    ?>
  </select>
  <button type="submit" class="btn btn-primary">Tampilkan</button>
</form>

  <div>
  
<tbody>
<?php
include 'connect.php';

$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$where = '';

if (!empty($tahun)) {
    $where = "WHERE tahun = '$tahun'";
}

$query = "SELECT 
            provinsi, 
            SUM(total_luas) AS total_serangan,
            SUM(luas_ringan) AS total_ringan,
            SUM(luas_berat) AS total_berat
          FROM serangan
          $where
          GROUP BY provinsi
          LIMIT 10";

$result = mysqli_query($koneksi, $query);

// Tabel hasil
echo "<table class='table'>";
echo "<tr>
        <th>No</th>
        <th>Provinsi</th>
        <th>Total Luas (Ha)</th>
        <th>Luas Ringan (Ha)</th>
        <th>Luas Berat (Ha)</th>
        <th>Aksi</th>
      </tr>";

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$no}</td>
        <td>{$row['provinsi']}</td>
        <td>{$row['total_serangan']}</td>
        <td>{$row['total_ringan']}</td>
        <td>{$row['total_berat']}</td>

        <td><a href='detail_provinsi.php?provinsi=" . urlencode($row['provinsi']) . "&tahun={$tahun}' class='btn btn-sm btn-primary'>Detail</a></td>
    </tr>";
    $no++;
}
echo "</table>";

?>

            </tbody>
        </table>
    </div>
     <form action="import_serangan.php" method="post" enctype="multipart/form-data" class="mb-3">
    <div class="input-group">
        <input type="file" name="file_csv" class="form-control" accept=".csv" required>
        <button type="submit" name="import" class="btn btn-success">Import CSV</button>
    </div>
</form>
  <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white" id="modalHeader">
        <h5 class="modal-title" id="statusModalLabel">Status</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalBody">
        <!-- Pesan akan diisi lewat JS -->
      </div>
    </div>
  </div>
</div>
</div></div>

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
