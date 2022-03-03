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
$spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->setCellValue('A1', 'PUESTO');
$sheet->setCellValue('B1', 'PLAZAS');

$sql = "SELECT nombre, (SELECT COUNT(*) FROM Plaza WHERE id_puesto = Puesto.id_puesto) AS plazas FROM Puesto";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $i = 2;
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->setCellValue('A' . $i, mb_strtoupper($res['nombre']));
        $sheet->setCellValue('B' . $i, $res['plazas']);
        $i++;
    }

    foreach (range('A', 'B') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $spreadsheet->getActiveSheet()->setAutoFilter('A1:B1');
}

$conexion->close();
$nombre = "puestos_".time().".xlsx";
$writer = new Xlsx($spreadsheet);
$writer->save('../archivos/'.$nombre);
echo "assets/archivos/".$nombre;
