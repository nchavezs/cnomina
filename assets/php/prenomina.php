<?php
require '../../vendor/autoload.php';
include "conexion.php";
setlocale(LC_ALL, "spanish");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$conexion = conexion();
session_start();

// $id_prenomina = $_SESSION["id_prenomina"];
$periodo = $_SESSION["id_periodo"];
$ano = date("Y");

$sql = "SELECT * FROM Prenomina WHERE 
YEAR(del) = ".$ano." AND 
id_periodo = ".$periodo." 
ORDER BY id_prenomina DESC LIMIT 1";

$consulta = $conexion->query($sql);
$prenomina = mysqli_fetch_array($consulta);

$del = $prenomina["del"];
$al = $prenomina["al"];
$observacion = trim($_POST["observacion"]) ?: 'Sin observaciones';

$ruta = './../prenominas/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

function logo($sheet)
{
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($sheet);
}

function dias_paga($val, $array)
{
    foreach ($array as $element) {
        if ($element["rfc"] == $val) {
            return $element['paga'];
        }
    }
    return null;
}

function dias_descontados($val, $array)
{
    foreach ($array as $element) {
        if ($element["rfc"] == $val) {
            return $element['dias'];
        }
    }
    return null;
}

function dias_permiso($val, $array)
{
    foreach ($array as $element) {
        if ($element["rfc"] == $val) {
            return $element['dias_permiso'];
        }
    }
    return null;
}

$descuentos = [];
$texto = mb_strtoupper(strftime(" del %e de %B", strtotime($del)).strftime(" al %e de %B", strtotime($al)).strftime(" del %Y", strtotime($del)));
$del = date("Y-m-d", strtotime(str_replace('/', '-', $del)));
$al = date("Y-m-d", strtotime(str_replace('/', '-', $al)));
$fecha1 = new DateTime($del);
$fecha2 = new DateTime($al);
$diff = $fecha1->diff($fecha2);
$dias_pago = $diff->format('%a') + 1;

$sql = "SELECT *,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT estado FROM Usuario WHERE RFC = Empleado.RFC) AS estado,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
    (SELECT nombre FROM Trabajador WHERE id_trabajador = Empleado.id_trabajador) AS tipoTrabajador
    FROM Empleado WHERE
    id_periodo = " . $periodo . " AND
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'";

$consulta = $conexion->query($sql);

mysqli_num_rows($consulta);

while ($usuario = mysqli_fetch_array($consulta)) {
    $fecha_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $usuario["fechaRelLab"])));
    if ($usuario["estado"] == 'alta') {
        if (($fecha_inicio >= $del) && ($fecha_inicio <= $al)) {
            $datetime = new DateTime($fecha_inicio);
            $interval = $datetime->diff($fecha2);
            $paga = $interval->format('%a') + 1;
        } else {
            $paga = $dias_pago;
        }
    } else {
        $sql1 = "SELECT fecha, dias FROM Baja WHERE RFC = '" . $usuario["RFC"] . "' ORDER BY id_baja DESC LIMIT 1";
        $consulta1 = mysqli_query($conexion, $sql1);
        $fecha_baja = mysqli_fetch_row($consulta1);
        if ($fecha_baja[1] == 1) {
            $paga = 0;
        } else {
            $fecha_baja = $fecha_baja[0];

            if (($fecha_baja >= $del) && ($fecha_inicio <= $al) && ($fecha_inicio >= $del)) {
                $datetime1 = new DateTime($fecha_inicio);
                $datetime2 = new DateTime($fecha_baja);
                $interval = $datetime1->diff($datetime2);
                $paga = $interval->format('%a') + 1;

            } else {
                $datetime1 = new DateTime($del);
                $datetime2 = new DateTime($fecha_baja);
                $interval = $datetime1->diff($datetime2);
                $paga = $interval->format('%a') + 1;
            }
        }
    }

    $datos = [];
    $descontados = 0;
    $descontados_permiso = 0;

    $sql1 = "SELECT fechas FROM Descuento WHERE RFC = '" . $usuario["RFC"] . "'";
    $consulta1 = mysqli_query($conexion, $sql1);
    if ($consulta1 && mysqli_num_rows($consulta1) > 0) {
        while ($resultado1 = mysqli_fetch_row($consulta1)) {
            $fechas = explode(",", $resultado1[0]);
            foreach ($fechas as $fecha) {
                $date = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));
                if ($date >= $del && $date <= $al) {
                    $paga--;
                    $descontados++;
                }
            }
        }
    }

    $sql2 = "SELECT * FROM Permiso WHERE RFC = '" . $usuario["RFC"] . "' AND categoria = 1 AND del <= '" . $al . "'";
    if (($consulta2 = mysqli_query($conexion, $sql2)) && (mysqli_num_rows($consulta2) > 0)) {
        while ($resultado1 = mysqli_fetch_array($consulta2)) {
            if ($resultado1['al'] >= $al) {
                if ($resultado1['del'] < $del) {
                    $fecha1 = $del;
                } else {
                    $fecha1 = $resultado1['del'];
                }

                if ($resultado1['al'] < $al) {
                    $fecha2 = $resultado1['al'];
                } else {
                    $fecha2 = $al;
                }

                $fecha1 = new DateTime($fecha1);
                $fecha2 = new DateTime($fecha2);
                $diff = $fecha1->diff($fecha2);
                $dias = $diff->format('%a') + 1;
            } else {
                if ($resultado1['del'] < $del) {
                    $fecha1 = $del;
                } else {
                    $fecha1 = $resultado1['del'];
                }
                $fecha1 = new DateTime($fecha1);
                $fecha2 = new DateTime($resultado1['al']);
                $diff = $fecha1->diff($fecha2);
                $dias = $diff->format('%a') + 1;
            }
            if ($dias <= 0) {
                $dias = 0;
            }
            $paga = $paga - $dias;
            $descontados_permiso = $descontados_permiso + $dias;
        }
    }

    if ($paga < 0) {
        $paga = 0;
    } else if ($paga > $dias_pago) {
        $paga = $dias_pago;
    }

    if ($descontados > $dias_pago) {
        $descontados = $dias_pago;
    }

    if ($descontados_permiso > $dias_pago) {
        $descontados_permiso = $dias_pago;
    }

    $datos["rfc"] = $usuario["RFC"];
    $datos['paga'] = $paga;
    $datos['dias'] = $descontados;
    $datos['dias_permiso'] = $descontados_permiso;
    array_push($descuentos, $datos);
}

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

$firma = [
    'font' => [
        'size' => 10,
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
];

$contenido2 = [
    'font' => [
        'size' => 10,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'top' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        'bottom' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        'left' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        'right' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0);

$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Movimientos');
$spreadsheet->addSheet($sheet);
$sheet->mergeCells('A1:B1');
$sheet->mergeCells('C1:K1');
$sheet->getStyle("C1")->applyFromArray($titulos);
$sheet->setCellValue('C1', 'MOVIMIENTOS' . $texto);
logo($sheet);
$sheet->getRowDimension('1')->setRowHeight(40);
$sheet->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$sheet->getStyle('A2:K2')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'CURP');
$sheet->setCellValue('D2', 'RFC');
$sheet->setCellValue('E2', 'FECHA DE INGRESO');
$sheet->setCellValue('F2', 'DIAS A PAGAR');
$sheet->setCellValue('G2', 'PUESTO ACTUAL');
$sheet->setCellValue('H2', 'PUESTO ANTERIOR');
$sheet->setCellValue('I2', 'DEPARTAMENTO ACTUAL');
$sheet->setCellValue('J2', 'DEPARTAMENTO ANTERIOR');
$sheet->setCellValue('K2', 'FECHA DE MOVIMIENTO');

$sql = "SELECT
    Empleado.id_empleado,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    Empleado.CURP,
    Empleado.RFC,
    Empleado.fechaRelLab,
    Movimiento.departamentoAnterior,
    Movimiento.puestoAnterior,
    Movimiento.departamento,
    Movimiento.puesto,
    Movimiento.fecha
    FROM Movimiento INNER JOIN Empleado ON Movimiento.RFC = Empleado.RFC WHERE
    id_periodo =  " . $periodo . " AND
    Movimiento.fecha >= '" . $del . "' AND
    Movimiento.fecha <= '" . $al . "'";

$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["CURP"]);
        $sheet->setCellValue('D' . $i, $res["RFC"]);
        $sheet->setCellValue('E' . $i, $res["fechaRelLab"]);
        $sheet->setCellValue('F' . $i, dias_paga($res['RFC'], $descuentos));
        $sheet->setCellValue('G' . $i, $res["puesto"]);
        $sheet->setCellValue('H' . $i, $res["departamento"]);
        $sheet->setCellValue('I' . $i, $res["puestoAnterior"]);
        $sheet->setCellValue('J' . $i, $res["departamentoAnterior"]);
        $sheet->setCellValue('K' . $i, date("d/m/Y", strtotime($res["fecha"])));
        $i++;
    }
}

$sheet->getStyle('A3:K' . $i)->applyFromArray($contenido);
$i = $i + 5;
$sheet->mergeCells('A' . $i . ':K' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "_____________________________________");
$i++;
$sheet->mergeCells('A' . $i . ':K' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
foreach (range('A', 'K') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Bajas');
$spreadsheet->addSheet($sheet);
$sheet->mergeCells('A1:B1');
$sheet->mergeCells('C1:J1');
$sheet->getStyle("C1")->applyFromArray($titulos);
$sheet->setCellValue('C1', 'BAJAS' . $texto);
logo($sheet);
$sheet->getRowDimension('1')->setRowHeight(40);
$sheet->getStyle('A2:J2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$sheet->getStyle('A2:J2')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'CURP');
$sheet->setCellValue('D2', 'RFC');
$sheet->setCellValue('E2', 'PUESTO');
$sheet->setCellValue('F2', 'DEPARTAMENTO');
$sheet->setCellValue('G2', 'FECHA DE INGRESO');
$sheet->setCellValue('H2', 'FECHA DE BAJA');
$sheet->setCellValue('I2', 'OBSERVACIONES');
$sheet->setCellValue('J2', 'DIAS A PAGAR');

$sql = "SELECT
    Empleado.id_empleado,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    Empleado.CURP,
    Empleado.RFC,
    Empleado.fechaRelLab,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
    Baja.fecha,
    Baja.razon
    FROM Baja INNER JOIN Empleado ON Baja.RFC = Empleado.RFC WHERE
    id_periodo =  " . $periodo . " AND
    Baja.fecha >= '" . $del . "' AND
    Baja.fecha <= '" . $al . "'";

$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["CURP"]);
        $sheet->setCellValue('D' . $i, $res["RFC"]);
        $sheet->setCellValue('E' . $i, $res["puesto"]);
        $sheet->setCellValue('F' . $i, $res["departamento"]);
        $sheet->setCellValue('G' . $i, $res["fechaRelLab"]);
        $sheet->setCellValue('H' . $i, date("d/m/Y", strtotime($res["fecha"])));
        $sheet->setCellValue('I' . $i, mb_strtoupper($res["razon"]));
        $sheet->setCellValue('J' . $i, dias_paga($res['RFC'], $descuentos));
        $i++;
    }
}

$sheet->getStyle('A3:J' . $i)->applyFromArray($contenido);
$i = $i + 5;
$sheet->mergeCells('A' . $i . ':J' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "_____________________________________");
$i++;
$sheet->mergeCells('A' . $i . ':J' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
foreach (range('A', 'J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Altas');
$spreadsheet->addSheet($sheet);
$sheet->mergeCells('A1:B1');
$sheet->mergeCells('C1:H1');
$sheet->getStyle("C1")->applyFromArray($titulos);
$sheet->setCellValue('C1', 'ALTAS' . $texto);
logo($sheet);
$sheet->getRowDimension('1')->setRowHeight(40);
$sheet->getStyle('A2:I2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$sheet->getStyle('A2:I2')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'CURP');
$sheet->setCellValue('D2', 'RFC');
$sheet->setCellValue('E2', 'PUESTO');
$sheet->setCellValue('F2', 'DEPARTAMENTO');
$sheet->setCellValue('G2', 'FECHA DE INGRESO');
$sheet->setCellValue('H2', 'DIAS A PAGAR');
$sheet->setCellValue('I2', 'OBSERVACIONES');

$sql = "SELECT *,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
    FROM Empleado WHERE
    id_periodo =  " . $periodo . " AND
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') >= '" . $del . "' AND
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'";

$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sql0 = "SELECT * FROM Reingreso WHERE RFC = '" . $res['RFC'] . "'";
        $consulta0 = mysqli_query($conexion, $sql0);
        $observaciones = "";
        if ($consulta0 && mysqli_num_rows($consulta0) > 0) {
            $observaciones = "REINGRESO";
        }

        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["CURP"]);
        $sheet->setCellValue('D' . $i, $res["RFC"]);
        $sheet->setCellValue('E' . $i, $res["puesto"]);
        $sheet->setCellValue('F' . $i, $res["departamento"]);
        $sheet->setCellValue('G' . $i, $res["fechaRelLab"]);
        $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
        $sheet->setCellValue('I' . $i, $observaciones);

        $i++;
    }
}

$sheet->getStyle('A3:I' . $i)->applyFromArray($contenido);
$i = $i + 5;
$sheet->mergeCells('A' . $i . ':I' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "_____________________________________");
$i++;
$sheet->mergeCells('A' . $i . ':I' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
foreach (range('A', 'I') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Descuentos');
$spreadsheet->addSheet($sheet);
$sheet->mergeCells('A1:B1');
$sheet->mergeCells('C1:J1');
$sheet->getStyle("C1")->applyFromArray($titulos);
$sheet->setCellValue('C1', 'DESCUENTOS' . $texto);
logo($sheet);
$sheet->getRowDimension('1')->setRowHeight(40);
$sheet->getStyle('A2:J2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$sheet->getStyle('A2:J2')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'CURP');
$sheet->setCellValue('D2', 'RFC');
$sheet->setCellValue('E2', 'PUESTO');
$sheet->setCellValue('F2', 'DEPARTAMENTO');
$sheet->setCellValue('G2', 'FECHA DE INGRESO');
$sheet->setCellValue('H2', 'DIAS A PAGAR');
$sheet->setCellValue('I2', 'DIAS DESCONTADOS');
$sheet->setCellValue('J2', 'OBSERVACIONES');

$sql = "SELECT
    Empleado.id_empleado,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    Empleado.CURP,
    Empleado.RFC,
    Empleado.fechaRelLab,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
    FROM Empleado WHERE
    id_periodo =  " . $periodo . " AND
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'";
$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $usuario = false;
        $sql1 = "SELECT * FROM Descuento WHERE RFC = '" . $res['RFC'] . "'";
        if (($consulta1 = mysqli_query($conexion, $sql1)) && (mysqli_num_rows($consulta1) > 0)) {
            $observaciones = [];
            while ($resultado1 = mysqli_fetch_row($consulta1)) {
                $fechas = explode(",", $resultado1[4]);
                $existe_descuento = false;
                foreach ($fechas as $fecha) {
                    $date = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));
                    if ($date >= $del && $date <= $al) {
                        $existe_descuento = true;
                        array_push($observaciones, $resultado1[5]);
                    }
                }
            }
            $observaciones = array_unique($observaciones);
            $observaciones = implode(", ", $observaciones);
            $usuario = true;
        }
        if ($usuario && $existe_descuento) {
            $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
            $sheet->setCellValue('C' . $i, $res["CURP"]);
            $sheet->setCellValue('D' . $i, $res["RFC"]);
            $sheet->setCellValue('E' . $i, $res["puesto"]);
            $sheet->setCellValue('F' . $i, $res["departamento"]);
            $sheet->setCellValue('G' . $i, $res["fechaRelLab"]);
            $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
            $sheet->setCellValue('I' . $i, dias_descontados($res['RFC'], $descuentos));
            $sheet->setCellValue('J' . $i, mb_strtoupper(($observaciones)));
            $i++;
        }
    }
}

$sheet->getStyle('A3:J' . $i)->applyFromArray($contenido);
$i = $i + 5;
$sheet->mergeCells('A' . $i . ':J' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "_____________________________________");
$i++;
$sheet->mergeCells('A' . $i . ':J' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
foreach (range('A', 'J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Licencias con goce');
$spreadsheet->addSheet($sheet);
$sheet->mergeCells('A1:B1');
$sheet->mergeCells('C1:K1');
$sheet->getStyle("C1")->applyFromArray($titulos);
$sheet->setCellValue('C1', 'LICENCIAS CON GOCE DE SUELDO' . $texto);
logo($sheet);
$sheet->getRowDimension('1')->setRowHeight(40);
$sheet->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$sheet->getStyle('A2:K2')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'CURP');
$sheet->setCellValue('D2', 'RFC');
$sheet->setCellValue('E2', 'PUESTO');
$sheet->setCellValue('F2', 'DEPARTAMENTO');
$sheet->setCellValue('G2', 'FECHA DE INGRESO');
$sheet->setCellValue('H2', 'DIAS A PAGAR');
$sheet->setCellValue('I2', 'FECHA');
$sheet->setCellValue('J2', 'DIAS DE LICENCIA');
$sheet->setCellValue('K2', 'OBSERVACIONES');

$sql = "SELECT
    Empleado.id_empleado,
    Empleado.CURP,
    Empleado.RFC,
    Empleado.fechaRelLab,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
    Permiso.del,
    Permiso.al,
    Permiso.dias,
    Permiso.descripcion
    FROM Permiso LEFT JOIN Empleado ON Permiso.RFC = Empleado.RFC WHERE
    id_periodo =  " . $periodo . " AND
    Permiso.al >= '" . $del . "' AND
    Permiso.del <= '" . $al . "' AND
    Permiso.categoria = 0
    ORDER BY Permiso.RFC ASC";
$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["CURP"]);
        $sheet->setCellValue('D' . $i, $res["RFC"]);
        $sheet->setCellValue('E' . $i, $res["puesto"]);
        $sheet->setCellValue('F' . $i, $res["departamento"]);
        $sheet->setCellValue('G' . $i, $res["fechaRelLab"]);
        $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
        $sheet->setCellValue('I' . $i, mb_strtoupper(strftime("DEL %d DE %B DE %G", strtotime($res["del"])) . strftime(" AL %d DE %B DE %G", strtotime($res["al"]))));
        $sheet->setCellValue('J' . $i, $res["dias"]);
        $sheet->setCellValue('K' . $i, mb_strtoupper($res["descripcion"]));
        $i++;
    }
}

$sheet->getStyle('A3:K' . $i)->applyFromArray($contenido);
$i = $i + 5;
$sheet->mergeCells('A' . $i . ':K' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "_____________________________________");
$i++;
$sheet->mergeCells('A' . $i . ':K' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
foreach (range('A', 'K') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Licencias sin goce');
$spreadsheet->addSheet($sheet);
$sheet->mergeCells('A1:B1');
$sheet->mergeCells('C1:K1');
$sheet->getStyle("C1")->applyFromArray($titulos);
$sheet->setCellValue('C1', 'LICENCIAS SIN GOCE DE SUELDO' . $texto);
logo($sheet);
$sheet->getRowDimension('1')->setRowHeight(40);
$sheet->getStyle('A2:L2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$sheet->getStyle('A2:L2')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'CURP');
$sheet->setCellValue('D2', 'RFC');
$sheet->setCellValue('E2', 'PUESTO');
$sheet->setCellValue('F2', 'DEPARTAMENTO');
$sheet->setCellValue('G2', 'FECHA DE INGRESO');
$sheet->setCellValue('H2', 'DIAS A PAGAR');
$sheet->setCellValue('I2', 'DIAS DESCONTADOS');
$sheet->setCellValue('J2', 'FECHA');
$sheet->setCellValue('K2', 'DIAS DE LICENCIA');
$sheet->setCellValue('L2', 'OBSERVACIONES');

$sql = "SELECT
    Empleado.id_empleado,
    Empleado.CURP,
    Empleado.RFC,
    Empleado.fechaRelLab,
    (SELECT estado FROM Usuario WHERE RFC = Empleado.RFC) AS estado,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
    Permiso.del,
    Permiso.al,
    Permiso.dias,
    Permiso.descripcion
    FROM Permiso LEFT JOIN Empleado ON Permiso.RFC = Empleado.RFC WHERE
    id_periodo =  " . $periodo . " AND
    Permiso.del <= '" . $al . "' AND
    Permiso.categoria = 1
    ORDER BY Permiso.RFC ASC";
$consulta = $conexion->query($sql);

$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["CURP"]);
        $sheet->setCellValue('D' . $i, $res["RFC"]);
        $sheet->setCellValue('E' . $i, $res["puesto"]);
        $sheet->setCellValue('F' . $i, $res["departamento"]);
        $sheet->setCellValue('G' . $i, $res["fechaRelLab"]);
        $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
        $sheet->setCellValue('I' . $i, dias_permiso($res['RFC'], $descuentos));
        $sheet->setCellValue('J' . $i, mb_strtoupper(strftime("DEL %d DE %B DE %G", strtotime($res["del"])) . strftime(" AL %d DE %B DE %G", strtotime($res["al"]))));
        $sheet->setCellValue('K' . $i, $res["dias"]);
        $sheet->setCellValue('L' . $i, mb_strtoupper($res["descripcion"]));
        $i++;
    }
}

$sheet->getStyle('A3:L' . $i)->applyFromArray($contenido);
$i = $i + 5;
$sheet->mergeCells('A' . $i . ':L' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "_____________________________________");
$i++;
$sheet->mergeCells('A' . $i . ':L' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
foreach (range('A', 'L') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

#------------------------------------------------------------------------------------------

$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Honorarios y Eventuales');
$spreadsheet->addSheet($sheet);
$sheet->mergeCells('A1:B1');
$sheet->mergeCells('C1:K1');
$sheet->getStyle("C1")->applyFromArray($titulos);
$sheet->setCellValue('C1', 'HONORARIOS Y EVENTUALES' . $texto);
logo($sheet);
$sheet->getRowDimension('1')->setRowHeight(40);
$sheet->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$sheet->getStyle('A2:K2')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'CURP');
$sheet->setCellValue('D2', 'RFC');
$sheet->setCellValue('E2', 'PUESTO');
$sheet->setCellValue('F2', 'DEPARTAMENTO');
$sheet->setCellValue('G2', 'FECHA DE INGRESO');
$sheet->setCellValue('H2', 'DIAS A PAGAR');
$sheet->setCellValue('I2', 'TIPO DE TRABAJADOR');
$sheet->setCellValue('J2', 'ESTADO DEL EMPLEADO');
$sheet->setCellValue('K2', 'OBSERVACIONES');

$sql = "SELECT *,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
    (SELECT nombre FROM Trabajador WHERE id_trabajador = Empleado.id_trabajador) AS tipoTrabajador
    FROM Empleado WHERE
    id_trabajador IN(3,4) AND
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'
    ORDER BY departamento";
$consulta = $conexion->query($sql);

$i = 3;
$departamentos = [];
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $sheet->getStyle('A3:K3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ffffff');
    while ($res = mysqli_fetch_array($consulta)) {
        $bandera = false;
        if ($res["estado"] === 'baja') {
            $sql1 = "SELECT RFC FROM Baja WHERE fecha >= '" . $del . "' AND fecha <= '" . $al . "' AND RFC = '" . $res["RFC"] . "'";
            $consulta1 = mysqli_query($conexion, $sql1);
            if ($consulta1 && (mysqli_num_rows($consulta1) > 0)) {
                $bandera = true;
            } else {
                $bandera = false;
            }
        } else {
            $bandera = true;
        }

        if ($bandera) {
            $sql0 = "SELECT * FROM Reingreso WHERE RFC = '" . $res['RFC'] . "'";
            $consulta0 = mysqli_query($conexion, $sql0);
            $observaciones = "";
            if ($consulta0 && mysqli_num_rows($consulta0) > 0) {
                $observaciones = "REINGRESO";
            }
            $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
            $sheet->setCellValue('C' . $i, $res["CURP"]);
            $sheet->setCellValue('D' . $i, $res["RFC"]);
            $sheet->setCellValue('E' . $i, $res["puesto"]);
            $sheet->setCellValue('F' . $i, $res["departamento"]);
            $sheet->setCellValue('G' . $i, $res["fechaRelLab"]);
            $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
            $sheet->setCellValue('I' . $i, $res['tipoTrabajador']);
            $sheet->setCellValue('J' . $i, mb_strtoupper($res["estado"]));
            $sheet->setCellValue('K' . $i, $observaciones);
            $sheet->getStyle('A' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('B' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('C' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('D' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('E' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('F' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('G' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('H' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('I' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('J' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('K' . $i)->applyFromArray($contenido2);

            $i++;
            array_push($departamentos, $res["departamento"]);
        }
    }
}

$i = 4;
$temp = false;
$color = 'ffffff';
for ($c = 0; $c < sizeof($departamentos) - 1; $c++) {
    $actual = $departamentos[$c];
    $siguiente = $departamentos[$c + 1];
    if ($actual !== $siguiente) {
        $temp = !$temp;
        if ($temp) {
            $color = 'ebf1de';
        } else {
            $color = 'ffffff';
        }
    }
    $sheet->getStyle('A' . $i . ':J' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($color);
    $i++;
}

$i = $i + 5;
$sheet->mergeCells('A' . $i . ':J' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "_____________________________________");
$i++;
$sheet->mergeCells('A' . $i . ':J' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
foreach (range('A', 'J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet->setAutoFilter('A2:J2');

#------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Prenomina');
$spreadsheet->addSheet($sheet);
$sheet->mergeCells('A1:B1');
$sheet->mergeCells('C1:K1');
$sheet->getStyle("C1")->applyFromArray($titulos);
$sheet->setCellValue('C1', 'PRENOMINA' . $texto);
logo($sheet);
$sheet->getRowDimension('1')->setRowHeight(40);
$sheet->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
$sheet->getStyle('A2:K2')->getFont()->getColor()->setRGB('FFFFFF');

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'CURP');
$sheet->setCellValue('D2', 'RFC');
$sheet->setCellValue('E2', 'PUESTO');
$sheet->setCellValue('F2', 'DEPARTAMENTO');
$sheet->setCellValue('G2', 'FECHA DE INGRESO');
$sheet->setCellValue('H2', 'DIAS A PAGAR');
$sheet->setCellValue('I2', 'TIPO DE TRABAJADOR');
$sheet->setCellValue('J2', 'ESTADO DEL EMPLEADO');
$sheet->setCellValue('K2', 'OBSERVACIONES');

$sql = "SELECT *,
    (SELECT estado FROM Usuario WHERE RFC = Empleado.RFC) AS estado,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
    (SELECT nombre FROM Trabajador WHERE id_trabajador = Empleado.id_trabajador) AS tipoTrabajador
    FROM Empleado WHERE
    id_periodo =  " . $periodo . " AND
    id_trabajador NOT IN(3,4) AND
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'
    ORDER BY departamento";
$consulta = $conexion->query($sql);
$i = 3;
$departamentos = [];
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $sheet->getStyle('A3:K3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ffffff');
    while ($res = mysqli_fetch_array($consulta)) {
        $bandera = false;
        if ($res["estado"] === 'baja') {
            $sql1 = "SELECT RFC FROM Baja WHERE fecha >= '" . $del . "' AND fecha <= '" . $al . "' AND RFC = '" . $res["RFC"] . "'";
            $consulta1 = mysqli_query($conexion, $sql1);
            if ($consulta1 && (mysqli_num_rows($consulta1) > 0)) {
                $bandera = true;
            } else {
                $bandera = false;
            }
        } else {
            $bandera = true;
        }

        if ($bandera) {
            $sql0 = "SELECT * FROM Reingreso WHERE RFC = '" . $res['RFC'] . "'";
            $consulta0 = mysqli_query($conexion, $sql0);
            $observaciones = "";
            if ($consulta0 && mysqli_num_rows($consulta0) > 0) {
                $observaciones = "REINGRESO";
            }
            $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
            $sheet->setCellValue('C' . $i, $res["CURP"]);
            $sheet->setCellValue('D' . $i, $res["RFC"]);
            $sheet->setCellValue('E' . $i, $res["puesto"]);
            $sheet->setCellValue('F' . $i, $res["departamento"]);
            $sheet->setCellValue('G' . $i, $res["fechaRelLab"]);
            $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
            $sheet->setCellValue('I' . $i, $res['tipoTrabajador']);
            $sheet->setCellValue('J' . $i, mb_strtoupper($res["estado"]));
            $sheet->setCellValue('K' . $i, $observaciones);
            $sheet->getStyle('A' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('B' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('C' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('D' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('E' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('F' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('G' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('H' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('I' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('J' . $i)->applyFromArray($contenido2);
            $sheet->getStyle('K' . $i)->applyFromArray($contenido2);

            $i++;
            array_push($departamentos, $res["departamento"]);
        }
    }
}

$i = 4;
$temp = false;
$color = 'ffffff';
for ($c = 0; $c < sizeof($departamentos) - 1; $c++) {
    $actual = $departamentos[$c];
    $siguiente = $departamentos[$c + 1];
    if ($actual !== $siguiente) {
        $temp = !$temp;
        if ($temp) {
            $color = 'ebf1de';
        } else {
            $color = 'ffffff';
        }
    }
    $sheet->getStyle('A' . $i . ':J' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($color);
    $i++;
}

$i = $i + 5;
$sheet->mergeCells('A' . $i . ':J' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "_____________________________________");
$i++;
$sheet->mergeCells('A' . $i . ':J' . $i);
$sheet->getStyle("A" . $i)->applyFromArray($firma);
$sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
foreach (range('A', 'J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet->setAutoFilter('A2:J2');

#------------------------------------------------------------------------------------------

$url = uniqid() . ".xlsx";
$ruta = $ruta . $url;

$sql = "UPDATE Prenomina SET observacion = '" . $observacion . "', url = '" . $url . "' WHERE id_prenomina = ".$prenomina["id_prenomina"];

if ($conexion->query($sql)) {
    $writer = new Xlsx($spreadsheet);
    $writer->save($ruta);

    echo $url;
} else {
    echo 0;
}

mysqli_close($conexion);
exit();
