<?php
include 'assets/php/conexion.php';
include 'pdf/PdfToText.phpclass';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conexion = conexion();
$ruta = "assets/nominas/";
$i = 2;

$sql = "
    SELECT
        a.RFC,
        MAX(a.id_archivo),
        a.nombre,
        a.nombreEmpleado,
        u.puesto,
        u.tipoTrabajador,
        u.departamento
    FROM Archivo a
    JOIN Usuario u ON a.RFC = u.RFC
    WHERE u.estado = 'alta'
    GROUP BY a.RFC";
$consulta = $conexion->query($sql);

// -----------------------------------------------------------------------------------------
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet()->setTitle("Empleados");
$sheet->setCellValue('A1', 'NOMBRE');
$sheet->setCellValue('B1', 'RFC');
$sheet->setCellValue('C1', 'PUESTO');
$sheet->setCellValue('D1', 'DEPARTAMENTO');
$sheet->setCellValue('E1', 'TIPO DE TRABAJADOR');
$sheet->setCellValue('F1', 'SUELDO');

while ($nomina = $consulta->fetch_assoc()) {
    $archivo = $ruta . $nomina["nombre"];
    $pdf = new PdfToText();
    $pdf->BlockSeparator = "|";
    $pdf->Separator = "|";
    $pdf->Options = 0x00000400;
    $pdf->Load($archivo);
    $pdf = mb_strtoupper($pdf->Text);

    $pdf = str_replace("\n", "|", $pdf);
    $pdf = str_replace("\r", "|\r", $pdf);
    $pdf = str_replace(":", "", $pdf);
    $pdf = preg_replace('/([|])\1+/', '|', $pdf);
    $pdf = str_replace("  ", " ", $pdf);
    $pdf = str_replace("Á", "A", $pdf);
    $pdf = str_replace("É", "E", $pdf);
    $pdf = str_replace("Í", "I", $pdf);
    $pdf = str_replace("Ó", "O", $pdf);
    $pdf = str_replace("Ú", "U", $pdf);

    $string = 'NETO DEL RECIBO $|';
    $sueldo = calcular($string, $pdf);
    // ---------------------------------------------------------------------------------------
    $i++;
    $sheet->setCellValue('A' . $i, $nomina["nombreEmpleado"]);
    $sheet->setCellValue('B' . $i, $nomina["RFC"]);
    $sheet->setCellValue('C' . $i, $nomina["puesto"]);
    $sheet->setCellValue('D' . $i, $nomina["departamento"]);
    $sheet->setCellValue('E' . $i, $nomina["tipoTrabajador"]);
    $sheet->setCellValue('F' . $i, $sueldo ?? 0);
}

$conexion->close();

$writer = new Xlsx($spreadsheet);

$nombre = 'sueldos.xlsx';
$writer->save($nombre);

echo $sueldo;


function calcular($string, $pdf)
{
    $pos1 = strpos($pdf, $string);
    if ($pos1 !== false) {
        $pos1 = $pos1 + strlen($string);
        $pos2 = strpos($pdf, "|", $pos1);
        return trim(substr($pdf, $pos1, ($pos2 - $pos1)));
    } else {
        return "";
    }
}