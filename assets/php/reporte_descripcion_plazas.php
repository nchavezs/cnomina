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

if ($del != "" || $al != "" || isset($_POST["puestos"])) {
    $conexion = conexion();
    $puestos = implode(",", $_POST["puestos"]);
    $del_explode = explode("/", $del);
    $ano = array_pop($del_explode);
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
    $sql = "SELECT Plaza.*,
    (SELECT nombre FROM Usuario WHERE RFC = Plaza.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Plaza.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Plaza.id_puesto)) AS departamento
    FROM Plaza WHERE Plaza.id_puesto IN (" . $puestos . ")";

    $consulta = mysqli_query($conexion, $sql);
    if ($consulta && mysqli_num_rows($consulta) > 0) {
        $ultimo = "J";
        $bandera = true;
        $i = 3;

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'PLAZAS');
        $spreadsheet->addSheet($sheet);
        logo($sheet);
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:' . $ultimo . '1');
        $sheet->getStyle("C1")->applyFromArray($titulos);
        $sheet->setCellValue('C1', "PLAZAS AL ".$al);
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('A2:' . $ultimo . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
        $sheet->getStyle('A2:' . $ultimo . '2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('A2', '# PLAZA');
        $sheet->setCellValue('B2', 'TRABAJADOR');
        $sheet->setCellValue('C2', 'PUESTO');
        $sheet->setCellValue('D2', 'DEPARTAMENTO');
        $sheet->setCellValue('E2', 'DIAS OCUPADOS');
        $sheet->setCellValue('F2', 'DIAS DESOCUPADOS');
        $sheet->setCellValue('G2', 'DIAS POR EJERCER');
        $sheet->setCellValue('H2', 'DIAS PRESUPUESTADOS');
        $sheet->setCellValue('I2', 'ESTADO');
        $sheet->setCellValue('J2', 'ELABORACION');

        while ($resultado = mysqli_fetch_array($consulta)) {
            $presupuestados = $resultado["dias"];
            $estado = "ALTA";
            $ocupados = 0;
            $ocupados_total = 0;
            $fin_ano = date("Y-m-d", strtotime($ano . "-12-31"));
            $vacantes = diferencia($fin_ano, $date2);
            if ($vacantes >= $presupuestados) {
                $vacantes = $presupuestados;
            }
            if ($resultado["RFC"] == null) {
                $resultado["nombre"] = "POR EJERCER";
            }

            if ($resultado["estado"] == 0) {
               $estado = "BAJA";
            }

            $sql = "SELECT * FROM Historial_Plaza WHERE
            id_plaza = " . $resultado["id_plaza"] . " AND 
            YEAR(fecha_inicio) = " . $ano;

            $consulta2 = mysqli_query($conexion, $sql);
            if ($consulta2 && mysqli_num_rows($consulta2) > 0) {
                while ($historial = mysqli_fetch_array($consulta2)) {
                    $fecha1 = $historial["fecha_inicio"];
                    if ($historial["fecha_fin"] != null) {
                        $fecha2 = $historial["fecha_fin"];
                    } else {
                        $fecha2 = $date2;
                    }
                    $ocupados = diferencia($fecha1, $fecha2);
                    $ocupados_total = $ocupados_total + $ocupados + 1;
                }
            }

            $desocupados = $presupuestados - $vacantes - $ocupados_total;
            if ($desocupados < 0) {
                $desocupados = 0;
            }

            $sheet->setCellValue('A' . $i, $resultado["id_plaza"]);
            $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombre']));
            $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['puesto']));
            $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['departamento']));
            $sheet->setCellValue('E' . $i, $ocupados_total);
            $sheet->setCellValue('F' . $i, $desocupados);
            $sheet->setCellValue('G' . $i, $vacantes);
            $sheet->setCellValue('H' . $i, $presupuestados);
            $sheet->setCellValue('I' . $i, $estado);
            $sheet->setCellValue('J' . $i, date("d/m/Y h:i A", strtotime($resultado['elaboracion'])));

            $i++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A3:' . $ultimo . $i)->applyFromArray($contenido);
        foreach (range('A', $ultimo) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setAutoFilter('A2:'.$ultimo.'2');
    }

    if ($bandera) {
        $writer = new Xlsx($spreadsheet);
        $uid = uniqid();
        $writer->save('../archivos/reporte_plaza_' . $uid . '.xlsx');
        echo "assets/archivos/reporte_plaza_" . $uid . ".xlsx";
    } else {
        echo "No se encontraron resultados.";
    }

    mysqli_close($conexion);
}
