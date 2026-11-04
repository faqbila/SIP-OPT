<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
include 'connect.php';

/**
 * ===============================
 * Ambil Data Gambar Grafik (POST)
 * ===============================
 */
$barImg  = $_POST['barImg'] ?? '';
$lineImg = $_POST['lineImg'] ?? '';

if (empty($barImg) && empty($lineImg)) {
    die("⚠ Grafik tidak terkirim. Tekan Export setelah grafik tampil di kabupaten.php");
}

/**
 * ===============================
 * Ambil Filter dari POST
 * ===============================
 */
// ===============================
// Ambil Filter dari POST
// ===============================
$tahun_awal  = $_POST['tahun_awal'] ?? '';
$tahun_akhir = $_POST['tahun_akhir'] ?? '';
$provinsi    = $_POST['provinsi']  ?? '';
$kabupaten   = $_POST['kabupaten'] ?? '';
$opt         = $_POST['opt']       ?? '';
$komoditas   = $_POST['komoditas'] ?? '';

// ===============================
// Susun WHERE Sesuai Filter
// ===============================
$where = [];

if ($provinsi)  $where[] = "provinsi = '".mysqli_real_escape_string($koneksi, $provinsi)."'";
if ($kabupaten) $where[] = "kabupaten = '".mysqli_real_escape_string($koneksi, $kabupaten)."'";
if ($opt)       $where[] = "opt = '".mysqli_real_escape_string($koneksi, $opt)."'";
if ($komoditas) $where[] = "komoditas = '".mysqli_real_escape_string($koneksi, $komoditas)."'";

// Filter tahun (rentang)
if ($tahun_awal && $tahun_akhir) {
    $where[] = "tahun BETWEEN ".intval($tahun_awal)." AND ".intval($tahun_akhir);
} elseif ($tahun_awal) {
    $where[] = "tahun >= ".intval($tahun_awal);
} elseif ($tahun_akhir) {
    $where[] = "tahun <= ".intval($tahun_akhir);
}

$whereSql = $where ? "WHERE ".implode(" AND ", $where) : "";

/**
 * ===============================
 * Query Data Serangan
 * ===============================
 */
$query = "
    SELECT tahun, triwulan, provinsi, kabupaten, komoditas, opt,
           luas_ringan, luas_berat, total_luas
    FROM serangan
    $whereSql
    ORDER BY tahun DESC, triwulan DESC, kabupaten ASC
";
$result = mysqli_query($koneksi, $query);

/**
 * ===============================
 * Bangun Tabel Data
 * ===============================
 */
$tabel = '
<h4>Data Serangan OPT (Hasil Filter)</h4>
<table border="1" cellspacing="0" cellpadding="5" width="100%" 
       style="border-collapse:collapse;font-size:12px;">
<thead>
<tr style="background:#f2f2f2;">
    <th>No</th>
    <th>Tahun</th>
    <th>Triwulan</th>
    <th>Provinsi</th>
    <th>Kabupaten</th>
    <th>Komoditas</th>
    <th>OPT</th>
    <th>Luas Ringan (Ha)</th>
    <th>Luas Berat (Ha)</th>
    <th>Total Luas (Ha)</th>
</tr>
</thead>
<tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $tabel .= '
    <tr>
        <td>'.$no++.'</td>
        <td>'.$row['tahun'].'</td>
        <td>T'.$row['triwulan'].'</td>
        <td>'.$row['provinsi'].'</td>
        <td>'.$row['kabupaten'].'</td>
        <td>'.$row['komoditas'].'</td>
        <td>'.$row['opt'].'</td>
        <td align="right">'.number_format($row['luas_ringan'], 2).'</td>
        <td align="right">'.number_format($row['luas_berat'], 2).'</td>
        <td align="right">'.number_format($row['total_luas'], 2).'</td>
    </tr>';
}
$tabel .= '</tbody></table>';

/**
 * ===============================
 * Susun HTML Laporan
 * ===============================
 */
$html = '
<h3 style="text-align:center;">Laporan Serangan OPT per Kabupaten</h3>
<p style="text-align:center;">Grafik & Tabel berdasarkan filter</p>
<hr>';

if (!empty($barImg)) {
    $html .= '
    <h4>Grafik Batang</h4>
    <img src="'.$barImg.'" style="width:100%;max-width:700px;"><br><br>';
}
if (!empty($lineImg)) {
    $html .= '
    <h4>Grafik Garis</h4>
    <img src="'.$lineImg.'" style="width:100%;max-width:700px;"><br><br>';
}

$html .= $tabel;

/**
 * ===============================
 * Generate PDF dengan Dompdf
 * ===============================
 */
$dompdf = new Dompdf(['isRemoteEnabled' => true]);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_kabupaten.pdf", ["Attachment" => false]);
