<?php
include 'connect.php';

$id_kabupaten = $_POST['id_kabupaten'];
$nama_kabupaten = $_POST['nama_kabupaten'];
$id_provinsi = $_POST['id_provinsi'];

mysqli_query($koneksi,"
UPDATE kabupaten
SET
nama_kabupaten='$nama_kabupaten',
id_provinsi='$id_provinsi'
WHERE id_kabupaten='$id_kabupaten'
");
header("Location: wilayah.php?status=update");