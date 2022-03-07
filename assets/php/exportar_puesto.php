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
$sheet->setCellValue('B1', 'DEPARTAMENTO');
$sheet->setCellValue('C1', 'CATEGORIA');

$sql = "SELECT 
Puesto.nombre, 
Departamento.nombre AS 'departamento',
(SELECT nombre FROM Trabajador WHERE id_trabajador = Puesto.id_trabajador) AS categoria 
FROM Puesto LEFT JOIN Departamento ON Puesto.id_departamento = Departamento.id_departamento";

$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $i = 2;
    while ($res = mysqli_fetch_array($consulta)) {
        $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(mb_strtoupper($res["nombre"]), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $spreadsheet->getActiveSheet()->getCell('B' . $i)->setValueExplicit(mb_strtoupper($res["departamento"]), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $spreadsheet->getActiveSheet()->getCell('C' . $i)->setValueExplicit(mb_strtoupper($res["categoria"]), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        $i++;
    }

    foreach (range('A', 'C') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $spreadsheet->getActiveSheet()->setAutoFilter('A1:C1');
}

$conexion->close();
$nombre = "puesto_".time().".xlsx";
$writer = new Xlsx($spreadsheet);
$writer->save('../archivos/'.$nombre);
echo "assets/archivos/".$nombre;