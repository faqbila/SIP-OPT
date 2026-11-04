<?php
include 'connect.php';

$id = $_GET['id'];

mysqli_query($koneksi,"
DELETE FROM kabupaten
WHERE id='$id'
");

header("Location: wilayah.php?status=hapus");