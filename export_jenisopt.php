<?php
require 'vendor/autoload.php'; // pastikan dompdf sudah di-install: composer require dompdf/dompdf
use Dompdf\Dompdf;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /**
     * ===============================
     * Ambil data gambar grafik (POST)
     * ===============================
     */
    $chartImg = $_POST['chartImg'] ?? '';
    if (empty($chartImg)) {
        die("Gambar grafik tidak ditemukan.");
    }

    // Hilangkan prefix base64 (hanya jika perlu embed manual)
    $chartImg   = str_replace('data:image/png;base64,', '', $chartImg);
    $chartImg   = str_replace(' ', '+', $chartImg);

    /**
     * ===============================
     * Ambil filter dari POST
     * ===============================
     */
    $komoditas   = $_POST['komoditas']   ?? '';
    $opt         = $_POST['opt']         ?? '';
    $provinsi    = $_POST['provinsi']    ?? [];
    $tahun_awal  = $_POST['tahun_awal']  ?? '';
    $tahun_akhir = $_POST['tahun_akhir'] ?? '';
    $triwulan    = $_POST['triwulan']    ?? '';

    // Info filter
    $filterInfo = "
        <h4>Filter Data</h4>
        <ul>
            <li><b>Komoditas:</b> {$komoditas}</li>
            <li><b>OPT:</b> {$opt}</li>
            <li><b>Provinsi:</b> " . implode(", ", (array)$provinsi) . "</li>
            <li><b>Tahun:</b> {$tahun_awal} - {$tahun_akhir}</li>
            <li><b>Triwulan:</b> {$triwulan}</li>
        </ul>
    ";

    /**
     * ===============================
     * HTML untuk PDF
     * ===============================
     */
    $html = "
        <html>
        <head>
            <style>
                body   { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
                h2     { text-align: center; margin-bottom: 20px; }
                h4     { margin-top: 20px; margin-bottom: 10px; }
                ul     { line-height: 1.6; }
                .chart { text-align: center; margin-top: 20px; }
            </style>
        </head>
        <body>
            <h2>Laporan Grafik Serangan OPT (Halaman Jenis OPT)</h2>
            {$filterInfo}
            <div class='chart'>
                <img src='data:image/png;base64,{$chartImg}' style='width:100%; max-width:700px;'>
            </div>
        </body>
        </html>
    ";

    /**
     * ===============================
     * Proses Dompdf
     * ===============================
     */
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("grafik_jenis_opt.pdf", ["Attachment" => false]);

} else {
    echo "Akses tidak valid.";
}
