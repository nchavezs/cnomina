<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];

require_once "../../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load("../docs/movimiento.xlsx");

$sql = "SELECT * FROM Configuracion";
$consulta = $conexion->query($sql);
$configuracion = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Historial WHERE id_historial = " . $id;
$consulta = $conexion->query($sql);
$historial = mysqli_fetch_array($consulta);

$sql = "SELECT *,
(SELECT nombre FROM Periodo WHERE id_periodo = Empleado.id_periodo) AS periodo,
(SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Empleado.id_puesto )) AS trabajador,
(SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto)) AS departamento 
FROM Empleado WHERE RFC = '" . $historial["RFC"] . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Prenomina WHERE id_periodo = " . $usuario["id_periodo"] . " AND YEAR(del) = " . date("Y", strtotime($historial["fecha"])) . " AND id_prenomina <= " . $historial["id_prenomina"];
$consulta = $conexion->query($sql);
$periodo = mysqli_num_rows($consulta);

$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('F5', date("d/m/Y", strtotime($historial["fecha"])));
$sheet->setCellValue('R5', $periodo);
$sheet->setCellValue('N5', "NO. DE PERIODO ".$usuario["periodo"]);
$sheet->setCellValue('M38', "SALARIO ".$usuario["periodo"]);

$sheet->setCellValue('F28', mb_strtoupper($usuario["nombres"]));
$sheet->setCellValue('F30', mb_strtoupper($usuario["apellidom"]));
$sheet->setCellValue('F32', mb_strtoupper($usuario["apellidop"]));
$sheet->setCellValue('P30', mb_strtoupper($usuario["CURP"]));
$sheet->setCellValue('P32', mb_strtoupper($usuario["RFC"]));
$sheet->setCellValue('H36', mb_strtoupper($usuario["departamento"]));
$sheet->setCellValue('E38', mb_strtoupper($usuario["puesto"]));

if($usuario["trabajador"] == "BASE"){
    $sheet->setCellValue('E43', "X");
}else if($usuario["trabajador"] == "CONFIANZA"){
    $sheet->setCellValue('L43', "X");
}else if($usuario["trabajador"] == "HONORARIOS"){
    $sheet->setCellValue('R43', "X");
}

if($historial["tipo"] == "alta"){
    $sheet->setCellValue('E11', "X");
}else if($historial["tipo"] == "baja"){
    $sheet->setCellValue('L11', "X");
}else if($historial["tipo"] == "reingreso"){
    $sheet->setCellValue('E13', "X");
}

$conexion->close();

$nombre = "historial_".time().".xlsx";
$writer = new Xlsx($spreadsheet);
$writer->save('../archivos/'.$nombre);
echo "assets/archivos/".$nombre;

