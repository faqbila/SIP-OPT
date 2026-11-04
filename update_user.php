<?php
include 'connect.php';

$id      = $_POST['id'];
$nama    = $_POST['nama'];
$jabatan = $_POST['jabatan'];
$alamat  = $_POST['alamat'];
$no_hp   = $_POST['no_hp'];

$query = mysqli_query($koneksi, "
    UPDATE users SET
        nama='$nama',
        jabatan='$jabatan',
        alamat='$alamat',

        no_hp='$no_hp'
    WHERE id='$id'
");

if($query){
    header("Location: users.php?status=update");
}else{
    echo mysqli_error($koneksi);
}
?>