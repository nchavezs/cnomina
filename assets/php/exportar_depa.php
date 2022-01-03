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
$sheet = $spreadsheet->getActiveSheet()->setTitle("Departamentos");
$spreadsheet->getActiveSheet()->getStyle('A1:A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('37548E');
$spreadsheet->getActiveSheet()->getStyle('A1:A1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

$sheet->setCellValue('A1', 'NOMBRE');

$sql = "SELECT * FROM Departamento";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $i = 2;
    while ($res = mysqli_fetch_array($consulta)) {
        $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(mb_strtoupper($res["nombre"]), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $i++;
    }

    foreach (range('A', 'A') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $spreadsheet->getActiveSheet()->setAutoFilter('A1:A1');
}

mysqli_close($conexion);

$writer = new Xlsx($spreadsheet);
$writer->save('../archivos/departamentos.xlsx');
echo "assets/archivos/departamentos.xlsx";
exit();
