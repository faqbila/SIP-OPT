<?php
session_start();
include('connect.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM login WHERE username = '$username'");
$data = mysqli_fetch_assoc($query);
$nama = $data['nama'];
?>
<?php
include ('connect.php');

// Hapus data jika parameter tersedia
if (isset($_GET['hapus_id_cuti'])) {
    $id_cuti = mysqli_real_escape_string($koneksi, $_GET['hapus_id_cuti']);
    $query = "DELETE FROM riwayat_cuti WHERE id='$id_cuti'";
    $hasil = mysqli_query($koneksi, $query);

   if ($hasil) {
    header("Location: riwayat_cuti.php?success=1");
    exit;
} else {
    header("Location: riwayat_cuti.php?success=0");
    exit;
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="logo2.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    
</body>
</html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="logo2.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-CUTI BBPPTP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="tampilan2.css">
</head>
<body >

 <!-- Sidebar -->
    <div class="sidebar">
    <img src="logo2.png" alt="Logo" style="width: 100px; margin-bottom: 15px; margin-left: 60px;">
    <h5 class="text-center">E-CUTI BBPPTP MEDAN</h5>
        <a href="home.php"><i class="bi bi-house"></i></i> Beranda</a>
    <a href="pegawai.php"><i class="bi bi-person-vcard"></i></i> Data Pegawai</a>
    <a href="Form_Cuti.php"><i class="bi bi-plus-circle"></i> Tambahkan</a>
    <a href="print_cuti.php"><i class="bi bi-printer"></i> Cetak</a>
    <a href="hapus_cuti.php"><i class="bi bi-trash"></i> Hapus</a>
    <a href="edit_cuti.php"><i class="bi bi-pencil-square"></i> Edit</a>
    <a href="riwayat_cuti.php"><i class="bi bi-clock-history"></i></i> Riwayat</a>
    <a href="home.php"><i class="bi bi-arrow-left"></i> Kembali</a>
    <a href="export_cuti.php" class="btn btn-success btn-dashboard"><i class="bi bi-file-earmark-x"></i> Ekspor Excel</a>
    <a href="cetak_cuti.php" class="btn btn-success btn-dashboard"><i class="bi bi-filetype-pdf"></i> Ekspor PDF</a>
</div>
    <!-- Konten -->
    <div class="content">
        <figure class="text-center">
            <blockquote class="blockquote">
                <p>Daftar Riwayat Cuti Pegawai</p>
            </blockquote>
        </figure>
   <div class="d-flex justify-content-between align-items-center p-2" style="background:#f1f1f1;">
    <small class="login">Login sebagai: <strong><?php echo $nama ?></strong></small>

    <form method="get" class="d-flex" style="gap: 5px;">
        <input type="text" name="cari" class="form-control form-control-sm"  placeholder="Cari nama atau NIP" value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
    </form>
</div>
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>jenis_cuti</th>
                        <th>Tanggal Cuti</th>
                        <th>Hingga</th>
                        <th>Cuti Sebelum</th>
                        <th>Cuti Setelah</th>
                        <th>Disetujui</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include ('connect.php');
                   $cari = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : '';

if ($cari != '') {
    $sql = mysqli_query($koneksi, "SELECT * FROM riwayat_cuti WHERE nama LIKE '%$cari%' OR nip LIKE '%$cari%' ORDER BY nama ASC");
} else {
   $sql = mysqli_query($koneksi, "
    SELECT rc.*, p.sisa_cuti 
    FROM riwayat_cuti rc 
    LEFT JOIN pegawai p ON rc.nip = p.username 
    ORDER BY rc.nama ASC, rc.sisa_cuti_setelah DESC
");

}
                    $no = 1;
                    while ($row = mysqli_fetch_array($sql)) {
                        echo "
                        <tr>
                            <td>".$no."</td>
                            <td>".$row['nama']."</td>
                            <td>".$row['nip']."</td>
                            <td>".$row['jenis_cuti']."</td>
                            <td>".$row['awal_tanggal']."</td>
                            <td>".$row['akhir_tanggal']."</td>
                            <td class='text-center'>".$row['sisa_cuti_sebelum']."</td>
                            <td class='text-center'>".$row['sisa_cuti_setelah']."</td>
                            <td>".$row['disetujui_oleh']."</td>
                             <td class='text-center'>
                            <a href='riwayat_cuti.php?hapus_id_cuti=".urlencode($row['id'])."' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">
                                <i class='bi bi-trash-fill text-danger'></i>
                            </a>
                        </td>
                            </tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
                    <form action="import_riwayat.php" method="post" enctype="multipart/form-data" class="mb-3">
    <div class="input-group">
        <input type="file" name="file_csv" class="form-control" accept=".csv" required>
        <button type="submit" name="import" class="btn btn-success">Import CSV</button>
    </div>
</form>
 <div class="d-flex justify-content-end mb-3 ">
            <div class="text-end">
                <a href="riwayat_cuti.php" class="btn btn-primary me-3">← Kembali</a>
            </div>
            <div class="text-end">
                <a href="cetak_riwayat.php" class="btn btn-primary me-3">  <i class='bi bi-filetype-pdf'></i> Cetak Pdf</a>
            </div>
            <div class="text-end">
                <a href="export_riwayat.php" class="btn btn-primary">  <i class='bi bi-printer'></i> Cetak Exel</a>
            </div>
        </div>
        </div>
        </div>
    </div>
<script>
  setTimeout(() => {
    const url = new URL(window.location.href);
    url.searchParams.delete('success');
    window.history.replaceState({}, document.title, url.pathname);
  }, 3000); // hilangkan parameter setelah 3 detik
</script>
</body>
</html>