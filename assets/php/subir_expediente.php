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

$documento = $_POST["id"];
$archivo = $_FILES['file']['name'];
$file = $_FILES['file']['tmp_name'];
$ext = pathinfo($archivo, PATHINFO_EXTENSION);
$rfc = mb_strtoupper(pathinfo($archivo, PATHINFO_FILENAME));
$nombre = uniqid().".".$ext;

if ($ext == "pdf") {
    $pdf = leer_pdf($file);

    // CONSTANCIA DE SITUACION FISCAL
    if ($documento == 7) {
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
}

$sql = "SELECT RFC FROM Empleado WHERE RFC = '$rfc'";
$query = $conexion->query($sql);
if ($query && mysqli_num_rows($query) > 0) {
    $sql = "SELECT * FROM Fichero WHERE RFC = '$rfc' AND id_documento = $documento";
    $query = $conexion->query($sql);
    $ruta = "ficheros/$rfc/";
    if (!file_exists("../$ruta")) {
        mkdir("../$ruta", 0777, true);
    }
    $url = $ruta.$nombre;

    if ($query && mysqli_num_rows($query) > 0) {
        $fichero = $query->fetch_assoc();
        $archivo_anterior = "../" . $fichero['url'];
        if ($fichero['url'] && file_exists($archivo_anterior)) {
            unlink($archivo_anterior);
        }

        $sql = "UPDATE Fichero SET url = '$url' WHERE RFC = '$rfc' AND id_documento = $documento";
        $query = $conexion->query($sql);
        echo 1;
    } else {
        $sql = "INSERT INTO Fichero(id_documento, RFC, url) VALUES('$documento','$rfc', '$url')";
        $query = $conexion->query($sql);
        echo 1;
    }

    move_uploaded_file($file, "../$url");
} else {
    echo 0;
}

$conexion->close();
