<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];

require_once "../../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load("../docs/movimiento.xlsx");

$sql = "SELECT * FROM Movimiento WHERE id_movimiento = " . $id;
$consulta = mysqli_query($conexion, $sql);
$res = mysqli_fetch_row($consulta);

$sql1 = "SELECT * FROM Usuario WHERE RFC = '" . $res[1]."'";
$consulta1 = mysqli_query($conexion, $sql1);
$res1 = mysqli_fetch_row($consulta1);

$contenido = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];

$letra = substr($res1[9], 10, -7);
if (strtoupper($letra) === "H") {
    $sexo = "M  ( x )       F  (   )";
} else if (strtoupper($letra) === "M") {
    $sexo = "M  (   )       F  ( x )";
} else {
    $sexo = "M  (   )       F  (   )";
}

$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A23', mb_strtoupper($res1[6]));
$sheet->setCellValue('D23', mb_strtoupper($res1[8]));
$sheet->setCellValue('B8', mb_strtoupper($res1[11]));
$sheet->setCellValue('D25', "38200");
$sheet->setCellValue('E25', "COMONFORT");
$sheet->setCellValue('G25', "GUANAJUATO");
$sheet->setCellValue('G8', $res1[10]);
$sheet->setCellValue('G4', str_pad($res[0], 5, '0', STR_PAD_LEFT));
$sheet->setCellValue('F23', $sexo);

$sheet->getStyle('A23')->applyFromArray($contenido);
$sheet->getStyle('D23')->applyFromArray($contenido);
$sheet->getStyle('B8')->applyFromArray($contenido);
$sheet->getStyle('D25')->applyFromArray($contenido);
$sheet->getStyle('E25')->applyFromArray($contenido);
$sheet->getStyle('G25')->applyFromArray($contenido);
$sheet->getStyle('G8')->applyFromArray($contenido);
$sheet->getStyle('G4')->applyFromArray($contenido);
$sheet->getStyle('F23')->applyFromArray($contenido);

mysqli_close($conexion);
$writer = new Xlsx($spreadsheet);
$writer->save('../archivos/movimiento.xlsx');
echo "assets/archivos/movimiento.xlsx";
exit();
