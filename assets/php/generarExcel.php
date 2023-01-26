<?php
session_start();
include "conexion.php";
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$id_periodo = $_SESSION["id_periodo"];
$conexion = conexion();
$estado = $_POST["estado"];

$ruta = '../archivos/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$contenido = [
	'font' => [
		'size' => 10,
	],
	'alignment' => [
		'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
		'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
	],
];

$sql = "SELECT Empleado.*,
    Empleado.RFC AS RFC,
    (SELECT nombre FROM Periodo WHERE id_periodo = Empleado.id_periodo) AS periodo,
	(SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
	(SELECT id_plaza FROM Plaza WHERE RFC = Empleado.RFC) AS plaza,
	(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
	(SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Empleado.id_puesto )) AS trabajador 
	FROM Empleado LEFT JOIN Usuario ON Usuario.RFC = Empleado.RFC WHERE id_periodo = ".$id_periodo." AND estado = '".$estado."'";

$consulta = $conexion->query($sql);

$ultimo = "N";
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet()->setTitle("Empleados");
$sheet->getStyle('A1:'.$ultimo.'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$sheet->getStyle('A1:'.$ultimo.'1')->getFont()->getColor()->setRGB('FFFFFF');
$sheet->setCellValue('A1', '# EMPLEADO');
$sheet->setCellValue('B1', 'NOMBRE(S)');
$sheet->setCellValue('C1', 'APELLIDO PATERNO');
$sheet->setCellValue('D1', 'APELLIDO MATERNO');
$sheet->setCellValue('E1', '# PLAZA');
$sheet->setCellValue('F1', 'FECHA DE INGRESO');
$sheet->setCellValue('G1', 'CURP');
$sheet->setCellValue('H1', 'RFC');
$sheet->setCellValue('I1', 'PUESTO');
$sheet->setCellValue('J1', 'DEPARTAMENTO');
$sheet->setCellValue('K1', 'TIPO DE TRABAJADOR');
$sheet->setCellValue('L1', 'CUENTA BANCARIA');
$sheet->setCellValue('M1', '# DE AFILIACIÓN');
$sheet->setCellValue('N1', 'PERIODO');

if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $i = 2;
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res["id_empleado"], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombres"]));
        $sheet->setCellValue('C' . $i, mb_strtoupper($res["apellidop"]));
        $sheet->setCellValue('D' . $i, mb_strtoupper($res["apellidom"]));
		$sheet->setCellValue('E' . $i, $res["plaza"]);
        $sheet->setCellValue('F' . $i, $res["fechaRelLab"]);
        $sheet->setCellValue('G' . $i, $res["CURP"]);
		$sheet->setCellValue('H' . $i, $res["RFC"]);
        $sheet->setCellValue('I' . $i, $res["puesto"]);
        $sheet->setCellValue('J' . $i, $res["departamento"]);
		$sheet->setCellValue('K' . $i, $res["trabajador"]);
        $sheet->setCellValue('L' . $i, $res["banca"]);
        $sheet->setCellValue('M' . $i, $res["afiliacion"]);
        $sheet->setCellValue('N' . $i, $res["periodo"]);
        $i++;
    }

    $sheet->setAutoFilter('A1:'.$ultimo.'1');
	// $sheet->getStyle("C1")->applyFromArray($titulos);
	$sheet->getStyle('A1:'.$ultimo.'' . $i)->applyFromArray($contenido);

    foreach (range('A', $ultimo) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
}

$conexion->close();

$writer = new Xlsx($spreadsheet);

$nombre = 'empleados_'.time().'.xlsx';
$writer->save('../archivos/'.$nombre);
echo "assets/archivos/".$nombre;
exit();
