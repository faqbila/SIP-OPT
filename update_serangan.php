<?php
include 'connect.php';

$id         = $_POST['id'];
$kabupaten  = $_POST['kabupaten'];
$komoditas  = $_POST['komoditas'];

$query = mysqli_query($koneksi,"
    UPDATE serangan
    SET
        kabupaten = '$kabupaten',
        komoditas = '$komoditas',
    WHERE id = '$id'
");

if($query){
    header("Location: " . $_SERVER['HTTP_REFERER']);
}else{
    echo "Gagal mengubah data";
}
exit;
?>