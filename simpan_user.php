<?php
include 'connect.php';

$username = $_POST['username'];
$password = $_POST['password'];
$nama     = $_POST['nama'];
$jabatan  = $_POST['jabatan'];
$alamat   = $_POST['alamat'];
$no_hp    = $_POST['no_hp'];
$role     = $_POST['role'];

$query = mysqli_query($koneksi,"
INSERT INTO users
(username,password,nama,jabatan,alamat,no_hp,role)
VALUES
('$username','$password','$nama','$jabatan','$alamat','$no_hp','$role')
");

if($query){
    header("Location: users.php?status=tambah");
}else{
    header("Location: users.php?status=gagal_tambah");
}