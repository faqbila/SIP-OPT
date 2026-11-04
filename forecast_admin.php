<?php
require 'session_admin.php';
include 'connect.php';


$provList=[];
$q=mysqli_query($koneksi,"SELECT DISTINCT provinsi FROM rekap_opt_provinsi ORDER BY provinsi");
while($r=mysqli_fetch_assoc($q)) $provList[]=$r['provinsi'];

$optList=[];
$q=mysqli_query($koneksi,"SELECT DISTINCT opt FROM rekap_opt_provinsi ORDER BY opt");
while($r=mysqli_fetch_assoc($q)) $optList[]=$r['opt'];

/* ==============================
   FILTER (HANYA 3)
============================== */

$komoditas   = $_GET['komoditas'] ?? '';
$provinsi    = $_GET['provinsi'] ?? '';
$optPilihan  = $_GET['opt'] ?? '';
$isFilter = isset($_GET['komoditas']) || isset($_GET['provinsi']) || isset($_GET['opt']);

$where=[];

if ($komoditas) $where[]="komoditas='".mysqli_real_escape_string($koneksi,$komoditas)."'";
if ($provinsi)  $where[]="provinsi='".mysqli_real_escape_string($koneksi,$provinsi)."'";
if ($optPilihan)$where[]="opt='".mysqli_real_escape_string($koneksi,$optPilihan)."'";

$whereSQL = $where ? "WHERE ".implode(" AND ",$where):"";

/* ==============================
   QUERY TRIWULAN
============================== */
$sql="
SELECT 
    tahun, 
    triwulan, 
    opt, 
    total_luas_serangan as total_luas
FROM rekap_opt_provinsi
$whereSQL
ORDER BY tahun, FIELD(triwulan,'I','II','III','IV')
";

$res=mysqli_query($koneksi,$sql);

$dataOPT=[];
$allPeriods=[];
$seriesFull = [];

$mapTri = ['I'=>1,'II'=>2,'III'=>3,'IV'=>4];

while($r=mysqli_fetch_assoc($res)){
    $thn=(int)$r['tahun'];
    $tri = $mapTri[$r['triwulan']] ?? 0;
    $opt=$r['opt'];
    $val=(float)$r['total_luas'];

    $label = $thn.' Q'.$tri;

    // tetap untuk grafik
    $dataOPT[$opt][$label] = $val;
    $allPeriods[$label]=true;

    // 🔥 TAMBAHAN INI (WAJIB)
    $seriesFull[$opt][] = [
        'tahun' => $thn,
        'triwulan' => $tri,
        'total_luas' => $val
    ];
}

/* ==============================
   SORT TRIWULAN
============================== */
$labelsYears=array_keys($allPeriods);

usort($labelsYears,function($a,$b){
    list($y1,$q1)=explode(' Q',$a);
    list($y2,$q2)=explode(' Q',$b);
    return ($y1==$y2)?($q1-$q2):($y1-$y2);
});

/* ==============================
   FLASK ARIMA
============================== */
function get_arima_forecast($series){

    $url = "https://forecast-api-production-7d02.up.railway.app/forecast";

    $payload = ["series" => $series];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json",
            'method'  => 'POST',
            'content' => json_encode($payload),
            'timeout' => 60
        ]
    ];

    $context  = stream_context_create($options);
   $result = file_get_contents($url, false, $context);

if($result === FALSE){
    die("GAGAL KONEK KE FLASK");
}



    if($result === FALSE) return [];

    $result = json_decode($result, true);

return [
    'forecast' => $result['forecast'] ?? [],
    'order' => $result['order'] ?? [0,0,0],
    'aic' => $result['aic'] ?? null,
    'd_manual' => $result['d_manual'] ?? null,
    'acf_img' => $result['acf_img'] ?? '',
    'pacf_img' => $result['pacf_img'] ?? ''
];
}

/* ==============================
   BUILD DATASET
============================== */
$datasets=[];

if(!empty($labelsYears) && ($komoditas || $provinsi || $optPilihan)){

    // buat label masa depan (8 triwulan)
    $last=end($labelsYears);
    list($lastYear,$lastQ)=explode(' Q',$last);

    $futureLabels=[];
    for($i=1;$i<=8;$i++){
        $lastQ++;
        if($lastQ>4){
            $lastQ=1;
            $lastYear++;
        }
        $futureLabels[]=$lastYear.' Q'.$lastQ;
    }

    $labelsYears=array_merge($labelsYears,$futureLabels);
foreach($dataOPT as $optName=>$series){

    if(empty($seriesFull[$optName])) continue;

    if($komoditas || $provinsi || $optPilihan){
    if(!isset($arimaResults[$optName])){
    $arimaResults[$optName] = get_arima_forecast($seriesFull[$optName]);
}

$arima = $arimaResults[$optName];
}else{
    $arima = [
        'forecast' => [],
        'order' => [0,0,0],
        'seasonal_order' => [0,0,0,0]
    ];
}

  $pred = $arima['forecast'] ?? [];
$order = $arima['order'] ?? [0,0,0];
$seasonal = $arima['seasonal_order'] ?? [0,0,0,0];

    // fallback jika ARIMA gagal
    if(empty($pred)){
        $lastData = end($seriesFull[$optName]);
        $last = $lastData['total_luas'] ?? 0;
        $pred = array_fill(0, 8, $last);
    }

 $historicalData = [];
$forecastData = [];
$i = 0;

// warna random biar beda tiap OPT
$color = 'rgb(' . rand(50,200) . ',' . rand(50,200) . ',' . rand(50,200) . ')';

foreach($labelsYears as $yr){
    if(isset($series[$yr])){
        $historicalData[] = $series[$yr];
        $forecastData[] = null; // biar garis putus
    }else{
        $historicalData[] = null;
        $forecastData[] = $pred[$i] ?? null;
        $i++;
    }
}

   $datasets[] = [
    'label' => "$optName (Historis)",
    'data' => $historicalData,
    'borderColor' => $color,
    'borderWidth' => 2,
    'tension' => 0.3,
    'fill' => false
];

$datasets[] = [
    'label' => "$optName (Forecast)",
    'data' => $forecastData,
    'borderColor' => $color,
    'borderDash' => [5,5],
    'borderWidth' => 2,
    'tension' => 0.3,
    'fill' => false
];

if(!$komoditas && !$provinsi && !$optPilihan){
    $labelsYears = [];
    $datasets = [];
}
}

    }


$labelsJson=json_encode($labelsYears);
$datasetsJson=json_encode($datasets);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <link rel="shortcut icon" href="logo2.png" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Proteksi BBPPTP — Peramalan</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="tampilan.css" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</head>

<body>
<!-- Sidebar -->
<div class="sidebar">
    <img src="logo2.png" alt="Logo" style="width: 100px; margin-bottom: 15px; margin-left: 60px;">
    <h5 class="text-center">OPT BBPPTP MEDAN</h5>
    <a href="home_admin.php"><i class="bi bi-house"></i> Beranda</a>
    <a href="data_opt.php"><i class="bi bi-database"></i> Data OPT </a>
     <a href="users.php"><i class="bi bi-people"></i> Pengguna </a>
    <a href="wilayah.php" class="fw-bold"><i class="bi bi-building"></i> Wilayah</a>
    <a href="forecast_admin.php" class="fw-bold"><i class="bi bi-graph-up-arrow"></i> Peramalan</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

    <div class="content">
       
        <div class="card mb-4 p-3 shadow-sm">
            <form method="get" class="row g-3 align-items-end">
                <!-- Komoditas -->
                <div class="col-md-3">
                    <label class="form-label">Komoditas</label>
                    <select name="komoditas" class="form-select form-select-sm">
                        <option value="">-- Semua --</option>
                        <?php
                        $q = mysqli_query($koneksi, "SELECT DISTINCT komoditas FROM rekap_opt_provinsi ORDER BY komoditas");    
                        while ($r = mysqli_fetch_assoc($q)) {
                            $val = htmlspecialchars($r['komoditas']);
                            $sel = ($komoditas == $r['komoditas']) ? 'selected' : '';
                            echo "<option value=\"{$val}\" {$sel}>{$val}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Provinsi -->
                <div class="col-md-3">
                    <label class="form-label">Provinsi</label>
                    <select name="provinsi" class="form-select form-select-sm">
                        <option value="">-- Semua --</option>
                        <?php foreach ($provList as $p): ?>
                            <?php $val = htmlspecialchars($p); $sel = ($provinsi == $p) ? 'selected' : ''; ?>
                            <option value="<?= $val ?>" <?= $sel ?>><?= $val ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- OPT (single select - isi filter tidak diubah) -->
                <div class="col-md-4">
                    <label class="form-label">Jenis OPT</label>
                    <select id="optSelect" name="opt" class="form-select form-select-sm">
                        <option value="">-- Semua OPT --</option>
                        <?php foreach ($optList as $o): ?>
                            <?php $val = htmlspecialchars($o); $sel = ($optPilihan == $o) ? 'selected' : ''; ?>
                            <option value="<?= $val ?>" <?= $sel ?>><?= $val ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tombol -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Terapkan</button>
                </div>
                <div class="col-md-2">
                    <a href="<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])) ?>" class="btn btn-secondary btn-sm w-100">Reset</a>
                </div>
            </form>
        </div>
        <div class="row mt-4">
    <div class="col-md-6">
        <h6>ACF</h6>
        <img id="acfImg" style="width:100%; border:1px solid #ccc;">
    </div>
    <div class="col-md-6">
        <h6>PACF</h6>
        <img id="pacfImg" style="width:100%; border:1px solid #ccc;">
        
    </div>
</div>

<div class="row mt-4">
<?php if($isFilter): ?>
<?php foreach($dataOPT as $optName => $series): ?>
    <?php
    if($isFilter){
    $arima = get_arima_forecast($seriesFull[$optName]);
}else{
    $arima = [
        'forecast' => [],
        'order' => [0,0,0],
        'seasonal_order' => [0,0,0,0]
    ];
}
    $order = $arima['order'] ?? [0,0,0];
    $seasonal = $arima['seasonal_order'] ?? [0,0,0,0];
    ?>
    <div class="col-md-4">
        <div class="card p-3 mb-3 shadow-sm">
            <b><?= $optName ?></b>
            <hr>
            ARIMA: (<?= implode(",", $order) ?>)<br>
           AIC: <?= $arima['aic'] ?? '-' ?><br>
d (ADF): <?= $arima['d_manual'] ?? '-' ?>
        </div>
    </div>
<?php endforeach; ?>
<?php endif; ?>
</div>

        <!-- Grafik -->
        <h5 class="mb-3">Grafik Garis Total Luas Serangan OPT (Triwulanan)</h5>
        <canvas id="grafikLine" height="100"></canvas>
   <div class="d-flex justify-content-end mb-3">
  <a href="export_forecast.php?<?= http_build_query($_GET) ?>" target="_blank" class="btn btn-primary btn-lg">
    <i class="bi bi-printer"></i> Download PDF
</a>
</div>
    </div>

<script>
document.getElementById("downloadPDF").addEventListener("click", function() {
    window.location.href = "export_forecast.php";
});
</script>
    <script>
        $(function () {
            $('#optSelect').select2({
                placeholder: "Pilih jenis OPT",
                allowClear: true,
                width: '100%'
            });
        });

        // Data untuk Chart
        const labels   = <?= $labelsJson ?>;
        const datasets = <?= $datasetsJson ?>;

       const canvas = document.getElementById('grafikLine');

if(canvas){
    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: { labels, datasets },
        options: {
            responsive: true,
            interaction: { mode: 'nearest', intersect: false },
            plugins: {
                legend: { position: 'bottom' }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Tahun & Triwulan' },
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Total Luas' }
                }
            },
            spanGaps: true
        }
    });
}
    </script>
    
<!-- 🔥 TAMBAHKAN DI SINI -->
<script>
<?php if($isFilter && !empty($seriesFull)): ?>

let firstOpt = <?= json_encode(array_values($seriesFull)[0]) ?>;

<?php if($isFilter && !empty($arimaResults)): ?>
document.getElementById('acfImg').src = "<?= reset($arimaResults)['acf_img'] ?>";
document.getElementById('pacfImg').src = "<?= reset($arimaResults)['pacf_img'] ?>";
<?php endif; ?>

.then(res => res.json())
.then(data => {
    if(data.acf_img){
        document.getElementById('acfImg').src = data.acf_img + '?t=' + new Date().getTime();
    }
    if(data.pacf_img){
        document.getElementById('pacfImg').src = data.pacf_img + '?t=' + new Date().getTime();
    }
});

<?php endif; ?>
</script>
    <script>
    // Update dropdown OPT saat komoditas berubah
$('select[name="komoditas"]').on('change', function() {
    let komoditas = $(this).val();
    $.getJSON('get_opt.php', { komoditas: komoditas }, function(data) {
        let $optSelect = $('#optSelect');
        $optSelect.empty().append('<option value="">-- Semua OPT --</option>');
        $.each(data, function(i, opt) {
            $optSelect.append('<option value="'+opt+'">'+opt+'</option>');
        });
        $optSelect.val('').trigger('change'); // reset pilihan
    });
});
</script>
</body>
</html>
