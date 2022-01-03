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
$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('37548E');
$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setRGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

$sheet->setCellValue('A1', 'PUESTO');
$sheet->setCellValue('B1', 'DEPARTAMENTO');
$sheet->setCellValue('C1', 'PLAZAS');

$sql = "SELECT Puesto.nombre, Departamento.nombre AS 'departamento', Puesto.cantidad FROM Puesto JOIN Departamento ON Puesto.id_departamento = Departamento.id_departamento";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $i = 2;
    while ($res = mysqli_fetch_array($consulta)) {
        $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(mb_strtoupper($res["nombre"]), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $spreadsheet->getActiveSheet()->getCell('B' . $i)->setValueExplicit(mb_strtoupper($res["departamento"]), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $spreadsheet->getActiveSheet()->setCellValue('C' . $i, $res["cantidad"]);

        $i++;
    }

    foreach (range('A', 'C') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $spreadsheet->getActiveSheet()->setAutoFilter('A1:C1');
}

mysqli_close($conexion);

$writer = new Xlsx($spreadsheet);
$writer->save('../archivos/puestos.xlsx');
echo "assets/archivos/puestos.xlsx";
exit();
