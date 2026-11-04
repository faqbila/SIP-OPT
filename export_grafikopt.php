<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barImg     = $_POST['barImg'] ?? '';
    $komoditas  = $_POST['komoditas'] ?? '';
    $provinsi   = $_POST['provinsi'] ?? '';
    $tahunAwal  = $_POST['tahun_awal'] ?? '';
    $tahunAkhir = $_POST['tahun_akhir'] ?? '';
    $triwulan   = $_POST['triwulan'] ?? '';
    $opt        = $_POST['opt'] ?? '';

    if (empty($barImg)) {
        die("Gambar grafik tidak ditemukan.");
    }

    // ================================
    // Ambil data serangan per tahun
    // ================================
    $query = "
        SELECT tahun, 
               SUM(luas_ringan) as ringan, 
               SUM(luas_berat) as berat, 
               SUM(total_luas) as total
        FROM serangan
        WHERE 1=1
    ";

    if ($komoditas)  $query .= " AND komoditas = '".mysqli_real_escape_string($koneksi, $komoditas)."'";
    if ($provinsi)   $query .= " AND provinsi = '".mysqli_real_escape_string($koneksi, $provinsi)."'";
    if ($triwulan)   $query .= " AND triwulan = '".mysqli_real_escape_string($koneksi, $triwulan)."'";
    if ($opt)        $query .= " AND opt = '".mysqli_real_escape_string($koneksi, $opt)."'";
    if ($tahunAwal && $tahunAkhir) {
        $query .= " AND tahun BETWEEN '".mysqli_real_escape_string($koneksi, $tahunAwal)."' 
                                AND '".mysqli_real_escape_string($koneksi, $tahunAkhir)."'";
    }

    $query .= " GROUP BY tahun ORDER BY tahun ASC";
    $result = mysqli_query($koneksi, $query);

    $tableRows = "";
    $grandRingan = $grandBerat = $grandTotal = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $tahun   = htmlspecialchars($row['tahun']);
        $ringan  = (float)$row['ringan'];
        $berat   = (float)$row['berat'];
        $total   = (float)$row['total'];

        $grandRingan += $ringan;
        $grandBerat  += $berat;
        $grandTotal  += $total;

        $tableRows .= "
            <tr>
                <td style='text-align:center'>{$tahun}</td>
                <td style='text-align:right'>".number_format($ringan,0,',','.')."</td>
                <td style='text-align:right'>".number_format($berat,0,',','.')."</td>
                <td style='text-align:right'>".number_format($total,0,',','.')."</td>
            </tr>
        ";
    }

    $tableHTML = "
        <h4>Rincian Total Serangan OPT per Tahun</h4>
        <table border='1' cellspacing='0' cellpadding='5' width='100%'>
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Luas Ringan (Ha)</th>
                    <th>Luas Berat (Ha)</th>
                    <th>Total Luas (Ha)</th>
                </tr>
            </thead>
            <tbody>
                {$tableRows}
                <tr style='font-weight:bold'>
                    <td style='text-align:center'>TOTAL</td>
                    <td style='text-align:right'>".number_format($grandRingan,0,',','.')."</td>
                    <td style='text-align:right'>".number_format($grandBerat,0,',','.')."</td>
                    <td style='text-align:right'>".number_format($grandTotal,0,',','.')."</td>
                </tr>
            </tbody>
        </table>
    ";

    // ================================
    // Filter Info
    // ================================
    $filterInfo = "
        <h4>Filter Data</h4>
        <ul>
            <li><b>Komoditas:</b> {$komoditas}</li>
            <li><b>Provinsi:</b> {$provinsi}</li>
            <li><b>Tahun:</b> {$tahunAwal} - {$tahunAkhir}</li>
            <li><b>Triwulan:</b> {$triwulan}</li>
            <li><b>OPT:</b> {$opt}</li>
        </ul>
    ";

    // ================================
    // HTML PDF
    // ================================
    $html = "
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
                h2 { text-align: center; margin-bottom: 15px; }
                h4 { margin-top: 20px; margin-bottom: 10px; }
                ul { line-height: 1.6; }
                .chart { text-align: center; margin: 20px 0; }
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #444; padding: 6px; font-size: 12px; }
                th { background: #f2f2f2; }
            </style>
        </head>
        <body>
            <h2>Laporan Grafik Serangan OPT</h2>
            {$filterInfo}
            <div class='chart'>
                <img src='{$barImg}' style='width:100%; max-width:700px;'>
            </div>
            {$tableHTML}
        </body>
        </html>
    ";

    // Generate PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("grafik_opt.pdf", ["Attachment" => false]);
} else {
    echo "Akses tidak valid.";
}
