<?php
include "connect.php";

// Ambil parameter filter
$tahun = $_GET['tahun'] ?? '';
$opt = $_GET['opt'] ?? '';
$komoditas = $_GET['komoditas'] ?? '';
$triwulan = $_GET['triwulan'] ?? '';

$where = [];
if ($tahun != '') {
    $where[] = "tahun = '".intval($tahun)."'";
}
if ($opt != '') {
    $where[] = "nama_opt = '".mysqli_real_escape_string($koneksi, $opt)."'";
}
if ($komoditas != '') {
    $where[] = "nama_komoditas = '".mysqli_real_escape_string($koneksi, $komoditas)."'";
}
if ($triwulan != '') {
    $where[] = "triwulan = '".intval($triwulan)."'";
}

$where_sql = $where ? "WHERE ".implode(" AND ", $where) : "";

// Ambil data per provinsi untuk grafik batang
$query = "
  SELECT provinsi,
         SUM(luas_ringan) AS luas_ringan,
         SUM(luas_berat) AS luas_berat,
         SUM(total_luas) AS total_serangan
  FROM serangan
  $where_sql
  GROUP BY provinsi
  ORDER BY total_serangan DESC
";

$result = mysqli_query($koneksi, $query);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        "provinsi" => $row['provinsi'],
        "luas_ringan" => (int)$row['luas_ringan'],
        "luas_berat" => (int)$row['luas_berat'],
        "total_serangan" => (int)$row['total_serangan']
    ];
}

echo json_encode($data);
