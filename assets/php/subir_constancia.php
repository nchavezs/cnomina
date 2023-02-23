<?php
setlocale(LC_ALL, "spanish");

include 'conexion.php';
include '../../pdf/PdfToText.php';

$conexion = conexion();

$ruta_nominas = "../expediente/";
if (!file_exists($ruta_nominas)) {
    mkdir($ruta_nominas, 0777, true);
}

$archivo = $_FILES['file']['tmp_name'];
$pdf = new PdfToText();
$pdf->BlockSeparator = "|";
$pdf->Separator = "|";
$pdf->Options = 0x00000400;
// $pdf->Options |= 0x00000000;
$pdf->Load($archivo );
$pdf = mb_strtoupper($pdf->Text);
$pdf = str_replace("\n", "|", $pdf);
$pdf = str_replace(":", "", $pdf);
$pdf = str_replace("  ", " ", $pdf);
$pdf = str_replace("Á", "A", $pdf);
$pdf = str_replace("É", "E", $pdf);
$pdf = str_replace("Í", "I", $pdf);
$pdf = str_replace("Ó", "O", $pdf);
$pdf = str_replace("Ú", "U", $pdf);
$pdf = preg_replace('/([|])\1+/', '|', $pdf);

$pos1 = strpos($pdf, 'RFC|');
if ($pos1 !== false) {
    $pos1 = $pos1 + 4;
    $pos2 = strpos($pdf, "|", $pos1);
    $rfc = trim(substr($pdf, $pos1, ($pos2 - $pos1)));
} else {
    $rfc = "";
}

$pos1 = strpos($pdf, 'POSTAL|');
if ($pos1 !== false) {
    $pos1 = $pos1 + 7;
    $pos2 = strpos($pdf, "|", $pos1);
    $postal = trim(substr($pdf, $pos1, ($pos2 - $pos1)));
} else {
    $postal = "";
}

$sql = "UPDATE "


$conexion->close();