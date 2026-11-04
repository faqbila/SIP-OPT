<?php
include 'connect.php';

// Ambil semua data dari tabel sementara
$result = mysqli_query($koneksi, "SELECT * FROM serangan_csv_raw");

while ($row = mysqli_fetch_assoc($result)) {
    $tahun = $row['tahun'];
    $triwulan = $row['triwulan'];
    $nama_opt = $row['nama_opt'];
    $nama_komoditas = $row['nama_komoditas'];
    $nama_provinsi = $row['nama_provinsi'];
    $nama_kabupaten = $row['nama_kabupaten'];
    $serangan_ringan = $row['serangan_ringan'];
    $serangan_berat = $row['serangan_berat'];
    $total = $serangan_ringan + $serangan_berat;

    // Cari ID berdasarkan nama
    $id_opt = getId($koneksi, "opt", "nama_opt", $nama_opt);
    $id_komoditas = getId($koneksi, "komoditas", "nama_komoditas", $nama_komoditas);
    $id_provinsi = getId($koneksi, "provinsi", "nama_provinsi", $nama_provinsi);
    $id_kabupaten = getId($koneksi, "kabupaten", "nama_kabupaten", $nama_kabupaten);

    // Jika semua ID ditemukan, masukkan ke tabel serangan
    if ($id_opt && $id_komoditas && $id_provinsi && $id_kabupaten) {
        $query = "INSERT INTO serangan 
        (tahun, triwulan, id_opt, id_komoditas, id_provinsi, id_kabupaten, serangan_ringan, serangan_berat, total_serangan) 
        VALUES 
        ('$tahun', '$triwulan', $id_opt, $id_komoditas, $id_provinsi, $id_kabupaten, $serangan_ringan, $serangan_berat, $total)";
        
        mysqli_query($koneksi, $query);
    } else {
        echo "Gagal input baris: " . json_encode($row) . "<br>";
    }
}

// Fungsi pencari ID berdasarkan nama
function getId($koneksi, $tabel, $kolom, $nama) {
    $nama = mysqli_real_escape_string($koneksi, trim($nama));
    $sql = "SELECT id FROM $tabel WHERE $kolom = '$nama' LIMIT 1";
    $result = mysqli_query($koneksi, $sql);

    if (!$result) {
        echo "Query error ($tabel): " . mysqli_error($koneksi) . "<br>";
        return null;
    }

    $data = mysqli_fetch_assoc($result);

    if ($data) {
        return $data['id'];
    } else {
        // Insert otomatis jika data belum ada
        $insert = mysqli_query($koneksi, "INSERT INTO $tabel ($kolom) VALUES ('$nama')");
        if ($insert) {
            return mysqli_insert_id($koneksi);
        } else {
            echo "Gagal insert ke tabel $tabel untuk nama $nama: " . mysqli_error($koneksi) . "<br>";
            return null;
        }
    }
}


echo "Proses konversi selesai.";
?>
