<?php
include "conexion.php";
$conexion = conexion();
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$ruta = '../archivos/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet()->setTitle("Puestos");
$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->setCellValue('A1', 'PUESTO');
$sheet->setCellValue('B1', 'PLAZAS');
$sheet->setCellValue('C1', 'CATEGORIA');

$sql = "SELECT nombre AS puesto,
(SELECT nombre FROM Trabajador WHERE id_trabajador = Puesto.id_trabajador LIMIT 1) AS categoria,
(SELECT COUNT(*) FROM Plaza WHERE id_puesto IN (SELECT id_puesto FROM Puesto WHERE nombre = puesto AND estado = 1)) AS plazas 
FROM Puesto GROUP BY nombre,categoria";

$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $i = 2;
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->setCellValue('A' . $i, mb_strtoupper($res['puesto']));
        $sheet->setCellValue('B' . $i, $res['plazas']);
        $sheet->setCellValue('C' . $i, $res['categoria']);
        $i++;
    }

    foreach (range('A', 'C') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $spreadsheet->getActiveSheet()->setAutoFilter('A1:C1');
}

$conexion->close();
$nombre = "puestos_".time().".xlsx";
$writer = new Xlsx($spreadsheet);
$writer->save('../archivos/'.$nombre);
echo "assets/archivos/".$nombre;
