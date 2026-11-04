<?php
include 'connect.php';

$komoditas = $_GET['komoditas'] ?? '';
$data = [];

if (!empty($komoditas)) {
    $komo = mysqli_real_escape_string($koneksi, $komoditas);
    $q = mysqli_query($koneksi, "SELECT DISTINCT opt FROM serangan WHERE komoditas='$komo' ORDER BY opt ASC");
    while ($r = mysqli_fetch_assoc($q)) {
        $data[] = $r['opt'];
    }
} else {
    $q = mysqli_query($koneksi, "SELECT DISTINCT opt FROM serangan ORDER BY opt ASC");
    while ($r = mysqli_fetch_assoc($q)) {
        $data[] = $r['opt'];
    }
}

header('Content-Type: application/json');
echo json_encode($data);
