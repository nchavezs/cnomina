<?php
$file = $_GET['filename'];

$n = explode("/", $file);
$url = "../".$n[1]."/".$n[2];

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=" . $n[2] . "");
header("Content-Transfer-Encoding: binary");
header("Content-Type: binary/octet-stream");
readfile($url);
