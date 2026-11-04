<?php
require 'session_admin.php';
include 'connect.php';

if (isset($_POST['import'])) {
    $file = $_FILES['file_csv']['tmp_name'];

    if ($_FILES['file_csv']['size'] > 0) {
        $handle = fopen($file, "r");

        $first = true;
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if ($first) { 
                $first = false; 
                continue; 
            }

            $tahun          = mysqli_real_escape_string($koneksi, $data[0]);

            // Konversi triwulan angka ke romawi
            $triwulan_input = trim($data[1]);
            $triwulan_romawi = match($triwulan_input) {
                '1', 'I' => 'I',
                '2', 'II' => 'II',
                '3', 'III' => 'III',
                '4', 'IV' => 'IV',
                default => NULL
            };

            $komoditas      = mysqli_real_escape_string($koneksi, $data[2]);
            $opt            = mysqli_real_escape_string($koneksi, $data[3]);
            $provinsi       = mysqli_real_escape_string($koneksi, $data[4]);
            $kabupaten      = mysqli_real_escape_string($koneksi, $data[5]);

            // Luas komoditas dari CSV (misalnya kolom ke-7)
            $luas_komoditas = floatval(str_replace(',', '.', str_replace('.', '', $data[6])));

            // Bersihkan dan konversi angka (format Indonesia ke float)
            $ringan = floatval(str_replace(',', '.', str_replace('.', '', $data[7])));
            $berat  = floatval(str_replace(',', '.', str_replace('.', '', $data[8])));
            $total  = $ringan + $berat;


$cek = mysqli_query($koneksi,"
    SELECT id_kabupaten
    FROM kabupaten
    WHERE id_kabupaten='$kabupaten'
");

if(mysqli_num_rows($cek)==0){
    echo "Tidak ditemukan: [$kabupaten]";
    exit;
}

            // Cek apakah triwulan valid
            if ($triwulan_romawi !== NULL) {
                $query = "INSERT INTO serangan 
                    (tahun, triwulan, komoditas, opt, provinsi, kabupaten, luas_komoditas, luas_ringan, luas_berat, total_luas) 
                    VALUES 
                    ('$tahun', '$triwulan_romawi', '$komoditas', '$opt', '$provinsi', '$kabupaten', $luas_komoditas, $ringan, $berat, $total)";
                mysqli_query($koneksi, $query);
            }
        }

        fclose($handle);
        echo "<script>window.location='data_opt.php?status=success';</script>";
    } else {
        echo "<script>window.location='data_opt.php?status=error';</script>";
    }
}
?>
