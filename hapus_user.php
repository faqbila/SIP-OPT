<?php
include 'connect.php';

$id = $_GET['id'];

if(mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'")){
    header("Location: users.php?status=hapus");
} else {
    header("Location: users.php?status=gagal_hapus");
}
exit;
?>