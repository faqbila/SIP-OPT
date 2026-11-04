<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $barImg = $_POST['barImg'] ?? '';
    if (empty($barImg)) {
        die("Gambar grafik tidak ditemukan.");
    }
    $imgSrc = $barImg;

    // Filter
    $tahun     = $_POST['tahun']     ?? '';
    $triwulan  = $_POST['triwulan']  ?? '';
    $komoditas = $_POST['komoditas'] ?? '';
    $opt       = $_POST['opt']       ?? '';

    $filterInfo = "
        <h4>Filter Data</h4>
        <ul>
            <li><b>Tahun:</b> {$tahun}</li>
            <li><b>Triwulan:</b> {$triwulan}</li>
            <li><b>Komoditas:</b> {$komoditas}</li>
            <li><b>OPT:</b> {$opt}</li>
        </ul>
    ";

    // ============================
    // Ambil data total luas per provinsi
    // ============================
   $provinsiData = $_POST['provinsiData'] ?? '[]';
$provinsiData = json_decode($provinsiData, true);

$tableRows = "";
$grandRingan = 0;
$grandBerat = 0;
$grandTotal = 0;

if (!empty($provinsiData)) {
    foreach ($provinsiData as $row) {
        $prov   = htmlspecialchars($row[0]);
        $ringan = (float) $row[1];
        $berat  = (float) $row[2];
        $total  = (float) $row[3];

        $grandRingan += $ringan;
        $grandBerat  += $berat;
        $grandTotal  += $total;

        $tableRows .= "
            <tr>
                <td>{$prov}</td>
                <td style='text-align:right'>".number_format($ringan,0,',','.')."</td>
                <td style='text-align:right'>".number_format($berat,0,',','.')."</td>
                <td style='text-align:right'>".number_format($total,0,',','.')."</td>
            </tr>";
    }
}

$tableHTML = "
    <h4>Rincian Serangan per Provinsi</h4>
    <table border='1' cellspacing='0' cellpadding='5' width='100%'>
        <thead>
            <tr>
                <th>Provinsi</th>
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

    }

    // ============================
    // HTML PDF
    // ============================
    $html = "
        <html>
        <head>
            <style>
                body   { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.5; }
                h2     { text-align: center; margin-bottom: 15px; }
                h4     { margin-top: 20px; margin-bottom: 10px; }
                ul     { margin: 0; padding-left: 18px; }
                .chart { text-align: center; margin-top: 20px; margin-bottom: 25px; }
                table  { border-collapse: collapse; margin-top: 15px; border: 1px solid #444; }
                th, td { border: 1px solid #444; font-size: 12px; }
                th     { background: #f2f2f2; }
            </style>
        </head>
        <body>
            <h2>Laporan Grafik Serangan OPT per Provinsi</h2>
            {$filterInfo}
            <div class='chart'>
                <img src='{$imgSrc}' style='width:100%; max-width:650px;'>
            </div>
            {$tableHTML}
        </body>
        </html>
    ";

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'potrait');
    $dompdf->render();
    $dompdf->stream("grafik_provinsi_opt.pdf", ["Attachment" => false]);

