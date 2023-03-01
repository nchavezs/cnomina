<?php
function leer_pdf($archivo)
{
    $pdf = new PdfToText();
    $pdf->BlockSeparator = "|";
    $pdf->Separator = "|";
    $pdf->Options = 0x00000400;
    $pdf->Load($archivo);
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
    return $pdf;
}

setlocale(LC_ALL, "spanish");

include 'conexion.php';
include '../../pdf/PdfToText.php';

$conexion = conexion();

$ruta = "../expediente/";
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$expediente = $_POST["id"];
$archivo = $_FILES['file']['name'];
$file = $_FILES['file']['tmp_name'];
$ext = pathinfo($archivo, PATHINFO_EXTENSION);
$nombre = basename($archivo);
$rfc = mb_strtoupper(pathinfo($archivo, PATHINFO_FILENAME));

if ($ext == "pdf" && $expediente == "constancia") {
    $pdf = leer_pdf($file);

    $pos1 = strpos($pdf, 'RFC|');
    if ($pos1 !== false) {
        $pos1 = $pos1 + 4;
        $pos2 = strpos($pdf, "|", $pos1);
        $rfc = trim(substr($pdf, $pos1, ($pos2 - $pos1)));
    }

    $pos1 = strpos($pdf, 'POSTAL|');
    if ($pos1 !== false) {
        $pos1 = $pos1 + 7;
        $pos2 = strpos($pdf, "|", $pos1);
        $postal = trim(substr($pdf, $pos1, ($pos2 - $pos1)));
    } else {
        $postal = "";
    }
}

$sql = "SELECT RFC FROM Empleado WHERE RFC = '" . $rfc . "'";
$query = $conexion->query($sql);
if ($query && mysqli_num_rows($query) > 0) {
    $sql = "SELECT RFC FROM Expediente WHERE RFC = '" . $rfc . "'";
    $query = $conexion->query($sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $sql = "UPDATE Expediente SET " . $expediente . " = 'assets/expediente/" . $nombre . "' WHERE RFC = '" . $rfc . "'";
        $query = $conexion->query($sql);
        echo 1;
    } else {
        $sql = "INSERT INTO Expediente(" . $expediente . ", RFC) VALUES('assets/expediente/" . $nombre . "','" . $rfc . "')";
        $query = $conexion->query($sql);
        echo 1;
    }

    $target = $ruta . $nombre;
    move_uploaded_file($file, $target);
}else{
    echo 0;
}

$conexion->close();
