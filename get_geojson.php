<?php
include "connect.php";

// Ambil parameter filter dari URL
$tahun     = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
$opt       = isset($_GET['opt']) ? $_GET['opt'] : '';
$komoditas = isset($_GET['komoditas']) ? $_GET['komoditas'] : '';
$triwulan  = isset($_GET['triwulan']) ? (int)$_GET['triwulan'] : 0;

// =======================
// 1) Bangun WHERE clause
// =======================
$where = ["tahun = '$tahun'"];

if ($opt !== '') {
    $where[] = "opt = '" . mysqli_real_escape_string($koneksi, $opt) . "'";
}
if ($komoditas !== '') {
    $where[] = "komoditas = '" . mysqli_real_escape_string($koneksi, $komoditas) . "'";
}
if ($triwulan > 0) {
    if ($triwulan == 1) $where[] = "bulan BETWEEN 1 AND 3";
    if ($triwulan == 2) $where[] = "bulan BETWEEN 4 AND 6";
    if ($triwulan == 3) $where[] = "bulan BETWEEN 7 AND 9";
    if ($triwulan == 4) $where[] = "bulan BETWEEN 10 AND 12";
}

$where_sql = implode(" AND ", $where);

// =======================
// 2) Query data serangan
// =======================
$query = "
    SELECT provinsi, SUM(total_luas) AS total_serangan
    FROM serangan
    WHERE $where_sql
    GROUP BY provinsi
";
$result = mysqli_query($koneksi, $query);

// Simpan hasil ke array lookup
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $prov = strtoupper(trim($row['provinsi']));
    $data[$prov] = (float)$row['total_serangan'];
}

// =======================
// 3) Ambil file GeoJSON
// =======================
$geojson = json_decode(file_get_contents('indonesia-province-simple.json'), true);

// =======================
// 4) Daftar provinsi Sumatera
// =======================
$sumatera = [
    "ACEH",
    "SUMATERA UTARA",
    "SUMATERA BARAT",
    "RIAU",
    "KEPULAUAN RIAU",
    "JAMBI",
    "SUMATERA SELATAN",
    "BANGKA BELITUNG",
    "BENGKULU",
    "LAMPUNG"
];

// =======================
// 5) Tambahkan data ke GeoJSON
// =======================
$newFeatures = [];
foreach ($geojson['features'] as $feature) {
    $props = $feature['properties'];

    // Nama provinsi di file JSON ada di field "Propinsi"
    $name = strtoupper(trim($props['Propinsi']));

    if (in_array($name, $sumatera)) {
        $feature['properties']['Propinsi'] = $name;
        $feature['properties']['total_serangan'] = $data[$name] ?? 0;
        $newFeatures[] = $feature;
    }
}

// =======================
// 6) Output final GeoJSON
// =======================
$output = [
    "type" => "FeatureCollection",
    "features" => $newFeatures
];

header('Content-Type: application/json');
echo json_encode($output);
