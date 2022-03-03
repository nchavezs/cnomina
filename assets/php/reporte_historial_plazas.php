<?php
setlocale(LC_ALL, "spanish");

include "conexion.php";
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function logo($sheet)
{
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($sheet);
}

function diferencia($fecha1, $fecha2)
{
    $fecha1 = new DateTime($fecha1);
    $fecha2 = new DateTime($fecha2);
    $diff = $fecha1->diff($fecha2);
    return $diff->format('%a');
}

$del = $_POST["del"];
$al = $_POST["al"];

if ($del != "" || $al != "" || isset($_POST["plazas"])) {
    $conexion = conexion();
    $plazas = implode(",", $_POST["plazas"]);
    $bandera = false;

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $del)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $al)));

    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0);

    $titulos = [
        'font' => [
            'size' => 20,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ];

    $contenido = [
        'font' => [
            'size' => 10,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ];
// ----------------- PLAZA --------------------
    $sql = "SELECT Historial_Plaza.*,
    (SELECT nombre FROM Usuario WHERE RFC = Historial_Plaza.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Plaza WHERE id_plaza = Historial_Plaza.id_plaza)) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Plaza WHERE id_plaza = Historial_Plaza.id_plaza))) AS departamento 
    FROM Historial_Plaza WHERE 
    Historial_Plaza.id_plaza IN (" . $plazas . ") AND 
    Historial_Plaza.fecha_inicio BETWEEN '" . $date1 . "' AND '" . $date2 . "'";

    $consulta = mysqli_query($conexion, $sql);
    if ($consulta && mysqli_num_rows($consulta) > 0) {
        $ultimo = "G";
        $bandera = true;
        $i = 3;

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'PLAZAS');
        $spreadsheet->addSheet($sheet);
        logo($sheet);
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:' . $ultimo . '1');
        $sheet->getStyle("C1")->applyFromArray($titulos);
        $sheet->setCellValue('C1', "HISTORIAL DE PLAZAS DEL " . $del . " AL " . $al);
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('A2:' . $ultimo . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
        $sheet->getStyle('A2:' . $ultimo . '2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('A2', '# PLAZA');
        $sheet->setCellValue('B2', 'TRABAJADOR');
        $sheet->setCellValue('C2', 'PUESTO');
        $sheet->setCellValue('D2', 'DEPARTAMENTO');
        $sheet->setCellValue('E2', 'DIAS OCUPADOS');
        $sheet->setCellValue('F2', 'FECHA DE INICIO');
        $sheet->setCellValue('G2', 'FECHA DE TERMINO');

        while ($resultado = mysqli_fetch_array($consulta)) {
            $fecha1 = new DateTime($resultado["fecha_inicio"]);
            if ($resultado["fecha_fin"] != "") {
                $fecha2 = new DateTime($resultado["fecha_fin"]);
                $fecha_termino=date("d/m/Y", strtotime($resultado['fecha_fin']));
            } else {
                $fecha2 = new DateTime($date2);
                $fecha_termino = "ACTIVO";
            }
            $diff = $fecha2->diff($fecha1);
            $ocupados = $diff->format('%a') + 1;

            $sheet->setCellValue('A' . $i, $resultado["id_plaza"]);
            $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombre']));
            $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['puesto']));
            $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['departamento']));
            $sheet->setCellValue('E' . $i, $ocupados);
            $sheet->setCellValue('F' . $i, date("d/m/Y", strtotime($resultado['fecha_inicio'])));
            $sheet->setCellValue('G' . $i, $fecha_termino);

            $i++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A3:' . $ultimo . $i)->applyFromArray($contenido);
        foreach (range('A', $ultimo) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setAutoFilter('A2:'.$ultimo.'2');
    }

    if ($bandera) {
        $nombre = "reporte_plaza_".time().".xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save('../archivos/'.$nombre);
        echo "assets/archivos/".$nombre;
    } else {
        echo "No se encontraron resultados.";
    }

    $conexion->close();
}
