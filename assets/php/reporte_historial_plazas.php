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
    $diff   = $fecha1->diff($fecha2);
    return $diff->format('%a');
}

$del           = $_POST["del"];
$al            = $_POST["al"];
$plazas        = $_POST["plazas"] ?? [];
$puestos       = $_POST["puestos"] ?? [];
$departamentos = $_POST["departamentos"] ?? [];

if ($del != "" || $al != "") {
    $conexion = conexion();
    $bandera  = false;

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $del)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $al)));

    if (sizeof($departamentos) > 0) {
        $array_depa = implode(",", $departamentos);
        if (sizeof($puestos) > 0) {
            $array_puestos = implode(",", $puestos);
            if (sizeof($plazas) > 0) {
                $array_plazas = implode(",", $plazas);
            } else {
                $sql      = "SELECT id_plaza FROM Plaza WHERE id_puesto IN ($array_puestos)";
                $consulta = $conexion->query($sql);
                while ($res = mysqli_fetch_row($consulta)) {
                    $plazas[] = $res[0];
                }
                $array_plazas = implode(",", $plazas);
            }
        } else {
            $sql      = "SELECT id_puesto FROM Puesto WHERE id_departamento IN ($array_depa)";
            $consulta = $conexion->query($sql);
            while ($res = mysqli_fetch_row($consulta)) {
                $puestos[] = $res[0];
            }
            $array_puestos = implode(",", $puestos);
            if (sizeof($plazas) > 0) {
                $array_plazas = implode(",", $plazas);
            } else {
                $sql      = "SELECT id_plaza FROM Plaza WHERE id_puesto IN ($array_puestos)";
                $consulta = $conexion->query($sql);
                while ($res = mysqli_fetch_row($consulta)) {
                    $plazas[] = $res[0];
                }
                $array_plazas = implode(",", $plazas);
            }
        }

        $extra = " AND Historial_Plaza.id_plaza IN ($array_plazas)";

    } else {
        $extra = "";
    }

    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0);

    $titulos = [
        'font'      => [
            'size' => 20,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ];

    $contenido = [
        'font'      => [
            'size' => 10,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ];

// ----------------- PLAZA --------------------

    // ---------------------------------------------------------------------------------------------------------
    $anio      = date("Y", strtotime($date1));
    $fecha_inicial = "$anio-12-31";
    $sql       = "SELECT id_plaza, dias FROM Plaza WHERE estado = 1 AND YEAR(elaboracion) = $anio";
    $consulta  = $conexion->query($sql);
    $ids_plaza = [];
    while ($fila = $consulta->fetch_assoc()) {
        $dias = $fila["dias"];

        $fecha = date('Y-m-d', strtotime("$fecha_inicial -$dias days"));

        if ( $fecha <= $date2 ) {
            $ids_plaza[] = $fila['id_plaza'];
        }
    }

    $sql_historial = "SELECT DISTINCT id_plaza FROM Historial_Plaza
    WHERE fecha_inicio <= '$date2' AND fecha_fin >= '$date1' AND YEAR(fecha_inicio) = $anio";

    $consulta      = $conexion->query($sql_historial);
    $ids_historial = [];

    while ($fila = $consulta->fetch_assoc()) {
        $ids_historial[] = $fila['id_plaza'];
    }

    $sin_plaza = array_diff($ids_plaza, $ids_historial);
    $sin_plaza = implode(",", $sin_plaza);
    // ---------------------------------------------------------------------------------------------------------

    $sql = "SELECT Historial_Plaza.*,
    (SELECT id_empleado FROM Empleado WHERE RFC = Historial_Plaza.RFC) as id_empleado,
    (SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Plaza WHERE id_plaza = Historial_Plaza.id_plaza))) AS categoria,
    (SELECT nombre FROM Usuario WHERE RFC = Historial_Plaza.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Plaza WHERE id_plaza = Historial_Plaza.id_plaza)) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Plaza WHERE id_plaza = Historial_Plaza.id_plaza))) AS departamento
    FROM Historial_Plaza WHERE
    fecha_fin >= '$date1' AND 
    fecha_inicio <= '$date2' AND YEAR(fecha_inicio) = '$anio'
    " . $extra . ' ORDER BY id_plaza, id_historial_plaza';

    $consulta = $conexion->query($sql);
    if ($consulta && mysqli_num_rows($consulta) > 0) {
        $ultimo  = "I";
        $bandera = true;
        $i       = 3;

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
        $sheet->setCellValue('B2', 'ID EMPLEADO');
        $sheet->setCellValue('C2', 'TRABAJADOR');
        $sheet->setCellValue('D2', 'CATEGORIA');
        $sheet->setCellValue('E2', 'PUESTO');
        $sheet->setCellValue('F2', 'DEPARTAMENTO');
        $sheet->setCellValue('G2', 'DIAS OCUPADOS');
        $sheet->setCellValue('H2', 'FECHA DE INICIO');
        $sheet->setCellValue('I2', 'FECHA DE TERMINO');

        $anterior = null;
        $nuevo = null;

        while ($resultado = mysqli_fetch_array($consulta)) {
            $nuevo = $resultado["id_plaza"];

            $fecha1 = new DateTime($resultado["fecha_inicio"]);
            if ($resultado["fecha_fin"] != "" && $resultado["fecha_fin"] <= $date2) {
                $fecha2        = new DateTime($resultado["fecha_fin"]);
                $fecha_termino = date("d/m/Y", strtotime($resultado['fecha_fin']));
            } else {
                $fecha2        = new DateTime($date2);
                $fecha_termino = "VIGENTE";
            }
            $diff     = $fecha2->diff($fecha1);
            $ocupados = $diff->format('%a') + 1;

            $sheet->setCellValue('A' . $i, $resultado["id_plaza"]);
            $sheet->setCellValue('B' . $i, str_pad($resultado["id_empleado"], 5, '0', STR_PAD_LEFT));
            $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['nombre']));
            $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['categoria']));
            $sheet->setCellValue('E' . $i, mb_strtoupper($resultado['puesto']));
            $sheet->setCellValue('F' . $i, mb_strtoupper($resultado['departamento']));
            $sheet->setCellValue('G' . $i, $ocupados);
            $sheet->setCellValue('H' . $i, date("d/m/Y", strtotime($resultado['fecha_inicio'])));
            $sheet->setCellValue('I' . $i, $fecha_termino);

            if ($anterior && $anterior != $nuevo) {
                $sheet->getStyle('A' . ($i-1) . ':' . $ultimo . ($i-1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ddd9c4');
            }

            $anterior = $resultado["id_plaza"];

            $i++;
        }

        if ($anterior && $anterior == $nuevo) {
            $sheet->getStyle('A' . ($i-1) . ':' . $ultimo . ($i-1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ddd9c4');
        }

        // ---------------------------------------------------------------------------------------------------------
        $sql = "SELECT Plaza.*,
        (SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Plaza.id_puesto)) AS categoria,
        (SELECT nombre FROM Puesto WHERE id_puesto = Plaza.id_puesto) AS puesto,
        (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Plaza.id_puesto)) AS departamento
        FROM Plaza WHERE id_plaza IN ($sin_plaza)";
        $consulta = $conexion->query($sql);

        while ($resultado = mysqli_fetch_array($consulta)) {
            $sheet->setCellValue('A' . $i, $resultado["id_plaza"]);
            $sheet->setCellValue('B' . $i, "");
            $sheet->setCellValue('C' . $i, "SIN EJERCER");
            $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['categoria']));
            $sheet->setCellValue('E' . $i, mb_strtoupper($resultado['puesto']));
            $sheet->setCellValue('F' . $i, mb_strtoupper($resultado['departamento']));
            $sheet->setCellValue('G' . $i, "");
            $sheet->setCellValue('H' . $i, "");
            $sheet->setCellValue('I' . $i, "VACANTE");

            $i++;
        }
        // ---------------------------------------------------------------------------------------------------------

        $spreadsheet->getActiveSheet()->getStyle('A3:' . $ultimo . $i)->applyFromArray($contenido);
        foreach (range('A', $ultimo) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setAutoFilter('A2:' . $ultimo . '2');
    }

    if ($bandera) {
        $nombre = "reporte_plaza_" . time() . ".xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save('../archivos/' . $nombre);
        echo "assets/archivos/" . $nombre;
    } else {
        echo "No se encontraron resultados.";
    }

    $conexion->close();
}
