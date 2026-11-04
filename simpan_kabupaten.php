<?php
include 'connect.php';

$nama_kabupaten = $_POST['nama_kabupaten'];
$id_provinsi = $_POST['id_provinsi'];

mysqli_query($koneksi,"
INSERT INTO kabupaten
(nama_kabupaten,id_provinsi)
VALUES
('$nama_kabupaten','$id_provinsi')
");

header("Location: wilayah.php?status=tambah");