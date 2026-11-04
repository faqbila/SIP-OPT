<?php

$file = "C:/xampp/htdocs/OPT BBPPTP MEDAN/secure.file/a0c1NnhlS1VWWnJEUEgvTy9OR2xjQT09MnJSYUpTMDxMYzhIUT09";

$data = file_get_contents($file);
$json = json_decode($data, true);

$pdf = str_replace(
    'data:application/pdf;base64,',
    '',
    $json['Original']
);

file_put_contents(
    'hasil2s.pdf',
    base64_decode($pdf)
);

echo "PDF berhasil dibuat";

?>