<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];

require_once "../../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load("../docs/movimiento.xlsx");

$sql = "SELECT * FROM Configuracion";
$consulta = mysqli_query($conexion, $sql);
$configuracion = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Movimiento WHERE id_movimiento = " . $id;
$consulta = mysqli_query($conexion, $sql);
$movimiento = mysqli_fetch_array($consulta);

$sql = "SELECT *,
(SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
(SELECT nombre FROM Trabajador WHERE id_trabajador = Empleado.id_trabajador) AS trabajador,
(SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto 
FROM Empleado WHERE RFC = '" . $movimiento["RFC"]."'";
$consulta = mysqli_query($conexion, $sql);
$usuario = mysqli_fetch_array($consulta);

$contenido = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];

$letra = substr($usuario["CURP"], 10, -7);
if (mb_strtoupper($letra) === "H") {
    $sexo = "M  ( x )       F  (   )";
} else if (mb_strtoupper($letra) === "M") {
    $sexo = "M  (   )       F  ( x )";
} else {
    $sexo = "M  (   )       F  (   )";
}

$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A23', mb_strtoupper($usuario["nombre"]));
$sheet->setCellValue('D23', mb_strtoupper($usuario["RFC"]));
$sheet->setCellValue('B8', mb_strtoupper($usuario["puesto"]));
$sheet->setCellValue('D25', "38200");
$sheet->setCellValue('E25', $configuracion["nombre"]);
$sheet->setCellValue('G25', "GUANAJUATO");
$sheet->setCellValue('G8', $usuario["fechaRelLab"]);
$sheet->setCellValue('G4', str_pad($movimiento["id_movimiento"], 5, '0', STR_PAD_LEFT));
$sheet->setCellValue('F23', $sexo);
$sheet->setCellValue('F30', $movimiento["observacion"]);
$sheet->setCellValue('A7', $usuario["trabajador"]);

$sheet->getStyle('A23')->applyFromArray($contenido);
$sheet->getStyle('D23')->applyFromArray($contenido);
$sheet->getStyle('B8')->applyFromArray($contenido);
$sheet->getStyle('D25')->applyFromArray($contenido);
$sheet->getStyle('E25')->applyFromArray($contenido);
$sheet->getStyle('G25')->applyFromArray($contenido);
$sheet->getStyle('G8')->applyFromArray($contenido);
$sheet->getStyle('G4')->applyFromArray($contenido);
$sheet->getStyle('F23')->applyFromArray($contenido);
$sheet->getStyle('F30')->applyFromArray($contenido);
$sheet->getStyle('A7')->applyFromArray($contenido);

mysqli_close($conexion);
$writer = new Xlsx($spreadsheet);
$writer->save('../archivos/movimiento.xlsx');
echo "assets/archivos/movimiento.xlsx";
exit();
