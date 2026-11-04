<?php
ob_start(); // Hindari output sebelum DomPDF
require 'vendor/autoload.php';
use Dompdf\Dompdf;
include 'connect.php';

/**
 * 1) Ambil filter dari GET
 */
$komoditas   = $_GET['komoditas']   ?? '';
$provinsi    = $_GET['provinsi']    ?? '';
$tahun_awal  = $_GET['tahun_awal']  ?? '';
$tahun_akhir = $_GET['tahun_akhir'] ?? '';
$triwulan    = $_GET['triwulan']    ?? '';
$optPilihan  = $_GET['opt']         ?? '';

/**
 * 2) Build WHERE dinamis
 */
$where = [];
if ($komoditas)   $where[] = "komoditas = '".mysqli_real_escape_string($koneksi,$komoditas)."'";
if ($provinsi)    $where[] = "provinsi  = '".mysqli_real_escape_string($koneksi,$provinsi)."'";
if ($optPilihan)  $where[] = "opt       = '".mysqli_real_escape_string($koneksi,$optPilihan)."'";
if ($tahun_awal)  $where[] = "tahun >= ".(int)$tahun_awal;
if ($tahun_akhir) $where[] = "tahun <= ".(int)$tahun_akhir;
if ($triwulan)    $where[] = "triwulan = '".mysqli_real_escape_string($koneksi,$triwulan)."'";

$whereSQL = $where ? ('WHERE ' . implode(' AND ', $where)) : "";

/**
 * 3) Ambil data historis (rata-rata per tahun)
 */
$sql = "
SELECT
    tahun,
    triwulan,
    total_luas_serangan AS total_luas
FROM rekap_opt_provinsi
$whereSQL
ORDER BY tahun,
FIELD(triwulan,'I','II','III','IV')
";
$result = mysqli_query($koneksi, $sql);

$labels = [];
$data = [];
$series = [];

while ($row = mysqli_fetch_assoc($result)) {

    $periode = $row['tahun'] . ' ' . $row['triwulan'];

    $labels[] = $periode;
    $data[] = (float)$row['total_luas'];

    $series[] = [
        'tahun'      => (int)$row['tahun'],
        'triwulan'   => $row['triwulan'],
        'total_luas' => (float)$row['total_luas']
    ];

    $tableHist .= "
    <tr>
        <td>{$periode}</td>
        <td>".number_format($row['total_luas'],2,',','.')." Ha</td>
    </tr>";
}


function get_arima_forecast($series){

    $url = "https://forecast-api-production-7d02.up.railway.app/forecast";

    $payload = [
        "series" => $series
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json",
            'method'  => 'POST',
            'content' => json_encode($payload),
            'timeout' => 60
        ]
    ];

    $context = stream_context_create($options);

    $result = file_get_contents($url,false,$context);

    if($result === FALSE){
        return [];
    }

    return json_decode($result,true);
}

$arima = get_arima_forecast($series);

$forecast = $arima['forecast'] ?? [];
$order    = $arima['order'] ?? [0,0,0];
$aic      = $arima['aic'] ?? '-';
$d_manual = $arima['d_manual'] ?? '-';

$tableForecast = "";

$lastData = end($series);

$lastYear = $lastData['tahun'];
$lastQ    = $lastData['triwulan'];

$mapTri = [
    'I' => 1,
    'II' => 2,
    'III' => 3,
    'IV' => 4
];

$qNum = $mapTri[$lastQ] ?? (int)$lastQ;

foreach($forecast as $yPred){

    $qNum++;

    if($qNum > 4){
        $qNum = 1;
        $lastYear++;
    }

    $qLabel = ['','I','II','III','IV'][$qNum];

    $periode = $lastYear.' '.$qLabel;

    $labels[] = $periode;
    $data[] = $yPred;

    $tableForecast .= "
    <tr>
        <td>{$periode}</td>
        <td>".number_format($yPred,2,',','.')." Ha</td>
    </tr>";
}

// =========================
// GRAFIK HISTORIS + ARIMA
// =========================

$width  = 900;
$height = 450;

$image = imagecreatetruecolor($width, $height);

// Warna
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$red   = imagecolorallocate($image, 220, 20, 60);
$blue  = imagecolorallocate($image, 30, 144, 255);
$gray  = imagecolorallocate($image, 220, 220, 220);

imagefilledrectangle($image, 0, 0, $width, $height, $white);

$padding = 60;

$chartWidth  = $width - ($padding * 2);
$chartHeight = $height - ($padding * 2);

$maxValue = max($data);

if($maxValue <= 0){
    $maxValue = 1;
}

$totalPoints = count($data);
$historicalCount = count($series);

// =========================
// GRID
// =========================

for($i=0; $i<=5; $i++){

    $y = $padding + ($i * $chartHeight / 5);

    imageline(
        $image,
        $padding,
        $y,
        $width-$padding,
        $y,
        $gray
    );

    $value = round(
        $maxValue - ($i * $maxValue / 5),
        2
    );

    imagestring(
        $image,
        2,
        5,
        $y-7,
        $value,
        $black
    );
}

// =========================
// AXIS
// =========================

imageline(
    $image,
    $padding,
    $padding,
    $padding,
    $height-$padding,
    $black
);

imageline(
    $image,
    $padding,
    $height-$padding,
    $width-$padding,
    $height-$padding,
    $black
);

// =========================
// GARIS HISTORIS
// =========================

for($i=0; $i<$historicalCount-1; $i++){

    $x1 = $padding + ($i * $chartWidth / ($totalPoints-1));
    $y1 = $height-$padding - (($data[$i]/$maxValue) * $chartHeight);

    $x2 = $padding + (($i+1) * $chartWidth / ($totalPoints-1));
    $y2 = $height-$padding - (($data[$i+1]/$maxValue) * $chartHeight);

    imageline($image,$x1,$y1,$x2,$y2,$red);

    imagefilledellipse(
        $image,
        $x1,
        $y1,
        6,
        6,
        $red
    );
}

// titik terakhir historis
$lastHistX = $padding + (($historicalCount-1) * $chartWidth / ($totalPoints-1));
$lastHistY = $height-$padding - (($data[$historicalCount-1]/$maxValue) * $chartHeight);

imagefilledellipse(
    $image,
    $lastHistX,
    $lastHistY,
    6,
    6,
    $red
);

// =========================
// GARIS FORECAST ARIMA
// =========================

for($i=$historicalCount-1; $i<$totalPoints-1; $i++){

    $x1 = $padding + ($i * $chartWidth / ($totalPoints-1));
    $y1 = $height-$padding - (($data[$i]/$maxValue) * $chartHeight);

    $x2 = $padding + (($i+1) * $chartWidth / ($totalPoints-1));
    $y2 = $height-$padding - (($data[$i+1]/$maxValue) * $chartHeight);

    imageline($image,$x1,$y1,$x2,$y2,$blue);

    imagefilledellipse(
        $image,
        $x2,
        $y2,
        6,
        6,
        $blue
    );
}

// =========================
// LABEL X
// =========================

$step = max(1, floor($totalPoints / 12));

for($i=0; $i<$totalPoints; $i+=$step){

    $x = $padding + ($i * $chartWidth / ($totalPoints-1));

    imagestring(
        $image,
        2,
        $x-15,
        $height-$padding+10,
        $labels[$i],
        $black
    );
}

// =========================
// JUDUL
// =========================

imagestring(
    $image,
    5,
    300,
    20,
    "Forecast ARIMA",
    $black
);

// =========================
// SIMPAN BASE64
// =========================

ob_start();

imagepng($image);

$chartData = ob_get_clean();

imagedestroy($image);

$base64 = 'data:image/png;base64,'.base64_encode($chartData);


$html .= '
<h4>Grafik Historis dan Forecast ARIMA</h4>

<div style="text-align:center;">
    <img src="'.$base64.'" width="700">
</div>
';

$html .= '
<h4>Data Historis</h4>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
<tr>
    <th>Periode</th>
    <th>Total Luas</th>
</tr>
'.$tableHist.'
</table>

<br>

<h4>Forecast ARIMA</h4>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
<tr>
    <th>Periode</th>
    <th>Prediksi</th>
</tr>
'.$tableForecast.'
</table>
';
$dompdf = new Dompdf();
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

ob_end_clean(); // bersihkan buffer biar PDF tidak corrupt
$dompdf->stream("grafik_opt_forecast.pdf", ["Attachment" => false]);
