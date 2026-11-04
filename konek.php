<?php
$koneksi = mysqli_connect("localhost", "root", "");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Pilih database yang akan digunakan
$nama_database = "pesona_seribu";
if (mysqli_select_db($koneksi, $nama_database)) {
    echo " ";
} else {
    echo "Tidak Ada database: " . mysqli_error($koneksi);
}