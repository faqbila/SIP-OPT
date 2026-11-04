<?php
include 'connect.php';

$komoditas = $_GET['komoditas'] ?? '';

if (!empty($komoditas)) {
    $stmt = mysqli_prepare($koneksi, "SELECT DISTINCT opt FROM serangan WHERE komoditas = ? ORDER BY opt ASC");
    mysqli_stmt_bind_param($stmt, "s", $komoditas);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $opts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $opts[] = $row['opt'];
    }
    echo json_encode($opts);
} else {
    // Jika tidak ada komoditas, tampilkan semua OPT
    $result = mysqli_query($koneksi, "SELECT DISTINCT opt FROM serangan ORDER BY opt ASC");
    $opts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $opts[] = $row['opt'];
    }
    echo json_encode($opts);
}
