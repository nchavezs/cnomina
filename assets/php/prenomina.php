<?php
require '../../vendor/autoload.php';
include "conexion.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');

$conexion = conexion();
$del_original = $_POST["del"];
$al_original = $_POST["al"];
$del = $_POST["del"];
$al = $_POST["al"];
$observacion = trim($_POST["observacion"]) ? : 'Sin observaciones';
$periodo = $_POST["periodo"];

$ruta = './../prenominas/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

function dias_paga($val, $array)
{
    foreach ($array as $element) {
        if ($element['id'] == $val) {
            return $element['paga'];
        }
    }
    return null;
}

function dias_descontados($val, $array)
{
    foreach ($array as $element) {
        if ($element['id'] == $val) {
            return $element['dias'];
        }
    }
    return null;
}

function dias_permiso($val, $array)
{
    foreach ($array as $element) {
        if ($element['id'] == $val) {
            return $element['dias_permiso'];
        }
    }
    return null;
}

function validar_fecha($date)
{
    $format = 'd/m/Y';
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

if (validar_fecha($del) && validar_fecha($al)) {

    $descuentos = [];
    $texto = " DEL ".$del_original." AL ".$al_original;

    $del = date("Y-m-d", strtotime(str_replace('/', '-', $del)));
    $al = date("Y-m-d", strtotime(str_replace('/', '-', $al)));
    $fecha1 = new DateTime($del);
    $fecha2 = new DateTime($al);
    $diff = $fecha1->diff($fecha2);
    $dias_pago = $diff->format('%a') + 1;

    $sql = "SELECT * FROM Usuario WHERE 
    categoria = 'user' AND 
    id_periodo = ".$periodo." AND 
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'";

    $consulta = mysqli_query($conexion, $sql);

    while ($usuario = mysqli_fetch_array($consulta)) {
        $fecha_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $usuario["fechaRelLab"])));
        if ($usuario["estado"] == 'alta') {
            if (($fecha_inicio >= $del) && ($fecha_inicio <= $al)) {
                // $datetime1 = new DateTime($al);
                $datetime = new DateTime($fecha_inicio);
                $interval = $fecha2->diff($datetime);
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
                if (($fecha_baja >= $del) && ($fecha_inicio <= $al)) {
                    $datetime1 = new DateTime($del);
                    $datetime2 = new DateTime($fecha_baja);
                    $interval = $datetime1->diff($datetime2);
                    $paga = $interval->format('%a') + 1;
                } else {
                    $paga = $dias_pago;
                }
            }
        }
        $datos = [];
        $descontados = 0;
        $descontados_permiso = 0;
        $sql1 = "SELECT fechas FROM Descuento WHERE RFC = '" . $usuario["RFC"] . "'";
        if (($consulta1 = mysqli_query($conexion, $sql1)) && (mysqli_num_rows($consulta1) > 0)) {
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

        if ($descontados > $dias_pago)
            $descontados = $dias_pago;

        if ($descontados_permiso > $dias_pago)
            $descontados_permiso = $dias_pago;

        $datos['id'] = $usuario["RFC"];
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
    $sheet = $spreadsheet->getActiveSheet()->setTitle("Movimientos");
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:K1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'MOVIMIENTOS' . $texto);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:K2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
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
    Usuario.id_usuario, 
    Usuario.nombre, 
    Usuario.CURP, 
    Usuario.RFC, 
    Usuario.fechaRelLab, 
    Movimiento.departamentoAnterior, 
    Movimiento.puestoAnterior, 
    Movimiento.departamento, 
    Movimiento.puesto, 
    Movimiento.fecha 
    FROM Movimiento INNER JOIN Usuario ON Movimiento.RFC = Usuario.RFC WHERE 
    id_periodo =  ".$periodo." AND 
    Movimiento.fecha >= '" . $del . "' AND 
    Movimiento.fecha <= '" . $al . "'";

    $consulta = mysqli_query($conexion, $sql);
    $i = 3;
    if ($consulta && (mysqli_num_rows($consulta) > 0)) {
        while ($res = mysqli_fetch_array($consulta)) {
            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($res['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res[1]));
            $sheet->setCellValue('C' . $i, $res[2]);
            $sheet->setCellValue('D' . $i, $res[3]);
            $sheet->setCellValue('E' . $i, $res[4]);
            $sheet->setCellValue('F' . $i, dias_paga($res['RFC'], $descuentos));
            $sheet->setCellValue('G' . $i, $res[8]);
            $sheet->setCellValue('H' . $i, $res[6]);
            $sheet->setCellValue('I' . $i, $res[7]);
            $sheet->setCellValue('J' . $i, $res[5]);
            $sheet->setCellValue('K' . $i, date("d/m/Y", strtotime($res[9])));
            $i++;
        }
    }

    $spreadsheet->getActiveSheet()->getStyle('A3:K' . $i)->applyFromArray($contenido);
    $i = $i + 5;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':K' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "_____________________________________");
    $i++;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':K' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
    foreach (range('A', 'K') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(1);
    $sheet = $spreadsheet->getActiveSheet()->setTitle('Bajas');
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:J1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'BAJAS' . $texto);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:J2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:J2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
    $sheet->setCellValue('B2', 'NOMBRE');
    $sheet->setCellValue('C2', 'CURP');
    $sheet->setCellValue('D2', 'RFC');
    $sheet->setCellValue('E2', 'PUESTO');
    $sheet->setCellValue('F2', 'DEPARTAMENTO');
    $sheet->setCellValue('G2', 'FECHA DE INGRESO');
    $sheet->setCellValue('H2', 'FECHA DE BAJA');
    $sheet->setCellValue('I2', 'OBSERVACIONES');
    $sheet->setCellValue('J2', 'DIAS A PAGAR');

    $sql = "SELECT Usuario.id_usuario, 
    Usuario.nombre, 
    Usuario.CURP, 
    Usuario.RFC, 
    Usuario.puesto, 
    Usuario.departamento, 
    Usuario.fechaRelLab, 
    Baja.fecha, 
    Baja.razon 
    FROM Baja INNER JOIN Usuario ON Baja.RFC = Usuario.RFC WHERE 
    id_periodo =  ".$periodo." AND 
    Baja.fecha >= '" . $del . "' AND 
    Baja.fecha <= '" . $al . "'";

    $consulta = mysqli_query($conexion, $sql);
    $i = 3;
    if ($consulta && (mysqli_num_rows($consulta) > 0)) {
        while ($res = mysqli_fetch_array($consulta)) {
            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($res['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res[1]));
            $sheet->setCellValue('C' . $i, $res[2]);
            $sheet->setCellValue('D' . $i, $res[3]);
            $sheet->setCellValue('E' . $i, $res[4]);
            $sheet->setCellValue('F' . $i, $res[5]);
            $sheet->setCellValue('G' . $i, $res[6]);
            $sheet->setCellValue('H' . $i, date("d/m/Y", strtotime($res[7])));
            $sheet->setCellValue('I' . $i, mb_strtoupper($res[8]));
            $sheet->setCellValue('J' . $i, dias_paga($res['RFC'], $descuentos));
            $i++;
        }
    }

    $spreadsheet->getActiveSheet()->getStyle('A3:J' . $i)->applyFromArray($contenido);
    $i = $i + 5;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':J' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "_____________________________________");
    $i++;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':J' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(2);
    $sheet = $spreadsheet->getActiveSheet()->setTitle('Altas');
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:H1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'ALTAS' . $texto);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:I2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:I2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
    $sheet->setCellValue('B2', 'NOMBRE');
    $sheet->setCellValue('C2', 'CURP');
    $sheet->setCellValue('D2', 'RFC');
    $sheet->setCellValue('E2', 'PUESTO');
    $sheet->setCellValue('F2', 'DEPARTAMENTO');
    $sheet->setCellValue('G2', 'FECHA DE INGRESO');
    $sheet->setCellValue('H2', 'DIAS A PAGAR');
    $sheet->setCellValue('I2', 'OBSERVACIONES');

    $sql = "SELECT id_usuario, 
    nombre, 
    CURP, 
    RFC, 
    puesto, 
    departamento, 
    fechaRelLab 
    FROM Usuario WHERE 
    id_periodo =  ".$periodo." AND 
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') >= '" . $del . "' AND 
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'";
    $consulta = mysqli_query($conexion, $sql);
    $i = 3;
    if ($consulta && (mysqli_num_rows($consulta) > 0)) {
        while ($res = mysqli_fetch_array($consulta)) {
            $sql0 = "SELECT * FROM Reingreso WHERE RFC = '" . $res['RFC'] . "'";
            $consulta0 = mysqli_query($conexion, $sql0);
            $observaciones = "";
            if ($consulta0 && mysqli_num_rows($consulta0) > 0) {
                $observaciones = "REINGRESO";
            }

            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($res['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res[1]));
            $sheet->setCellValue('C' . $i, $res[2]);
            $sheet->setCellValue('D' . $i, $res[3]);
            $sheet->setCellValue('E' . $i, $res[4]);
            $sheet->setCellValue('F' . $i, $res[5]);
            $sheet->setCellValue('G' . $i, $res[6]);
            $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
            $sheet->setCellValue('I' . $i, $observaciones);

            $i++;
        }
    }

    $spreadsheet->getActiveSheet()->getStyle('A3:I' . $i)->applyFromArray($contenido);
    $i = $i + 5;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':I' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "_____________________________________");
    $i++;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':I' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
    foreach (range('A', 'I') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(3);
    $sheet = $spreadsheet->getActiveSheet()->setTitle('Descuentos');
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:J1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'DESCUENTOS' . $texto);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:J2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:J2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
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
    id_usuario, 
    nombre, 
    CURP, 
    RFC, 
    puesto, 
    departamento, 
    fechaRelLab 
    FROM Usuario WHERE 
    id_periodo =  ".$periodo." AND 
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'";
    $consulta = mysqli_query($conexion, $sql);
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
                $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($res['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $i, mb_strtoupper($res[1]));
                $sheet->setCellValue('C' . $i, $res[2]);
                $sheet->setCellValue('D' . $i, $res[3]);
                $sheet->setCellValue('E' . $i, $res[4]);
                $sheet->setCellValue('F' . $i, $res[5]);
                $sheet->setCellValue('G' . $i, $res[6]);
                $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
                $sheet->setCellValue('I' . $i, dias_descontados($res['RFC'], $descuentos));
                $sheet->setCellValue('J' . $i, mb_strtoupper(($observaciones)));
                $i++;
            }
        }
    }

    $spreadsheet->getActiveSheet()->getStyle('A3:J' . $i)->applyFromArray($contenido);
    $i = $i + 5;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':J' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "_____________________________________");
    $i++;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':J' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(4);
    $sheet = $spreadsheet->getActiveSheet()->setTitle('Licencia con goce');
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:K1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'LICENCIA CON GOCE DE SUELDO' . $texto);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:K2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
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
    Usuario.id_usuario, 
    Usuario.nombre, 
    Usuario.CURP, 
    Usuario.RFC, 
    Usuario.puesto, 
    Usuario.departamento, 
    Usuario.fechaRelLab,
    Permiso.del, 
    Permiso.al, 
    Permiso.dias, 
    Permiso.descripcion 
    FROM Permiso INNER JOIN Usuario ON Permiso.RFC = Usuario.RFC WHERE 
    id_periodo =  ".$periodo." AND 
    Permiso.al >= '" . $del . "' AND 
    Permiso.del <= '" . $al . "' AND 
    Permiso.categoria = 0 
    ORDER BY Permiso.RFC ASC";
    $consulta = mysqli_query($conexion, $sql);
    $i = 3;
    if ($consulta && (mysqli_num_rows($consulta) > 0)) {
        while ($res = mysqli_fetch_array($consulta)) {
            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($res['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res[1]));
            $sheet->setCellValue('C' . $i, $res[2]);
            $sheet->setCellValue('D' . $i, $res[3]);
            $sheet->setCellValue('E' . $i, $res[4]);
            $sheet->setCellValue('F' . $i, $res[5]);
            $sheet->setCellValue('G' . $i, $res[6]);
            $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
            $sheet->setCellValue('I' . $i, mb_strtoupper(strftime("DEL %d DE %B DE %G", strtotime($res[7])) . strftime(" AL %d DE %B DE %G", strtotime($res[8]))));
            $sheet->setCellValue('J' . $i, $res[9]);
            $sheet->setCellValue('K' . $i, mb_strtoupper($res[10]));
            $i++;
        }
    }

    $spreadsheet->getActiveSheet()->getStyle('A3:K' . $i)->applyFromArray($contenido);
    $i = $i + 5;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':K' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "_____________________________________");
    $i++;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':K' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
    foreach (range('A', 'K') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(5);
    $sheet = $spreadsheet->getActiveSheet()->setTitle('Licencia sin goce');
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:K1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'LICENCIA SIN GOCE DE SUELDO' . $texto);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:L2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:L2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
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
    Usuario.id_usuario, 
    Usuario.nombre, 
    Usuario.CURP, 
    Usuario.RFC, 
    Usuario.puesto, 
    Usuario.departamento, 
    Usuario.fechaRelLab,
    Permiso.del, 
    Permiso.al, 
    Permiso.dias, 
    Permiso.descripcion 
    FROM Permiso INNER JOIN Usuario ON Permiso.RFC = Usuario.RFC WHERE 
    id_periodo =  ".$periodo." AND 
    Permiso.del <= '" . $al . "' AND 
    Permiso.categoria = 1 
    ORDER BY Permiso.RFC ASC";
    $consulta = mysqli_query($conexion, $sql);
    $i = 3;
    if ($consulta && (mysqli_num_rows($consulta) > 0)) {
        while ($res = mysqli_fetch_array($consulta)) {
            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($res['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res[1]));
            $sheet->setCellValue('C' . $i, $res[2]);
            $sheet->setCellValue('D' . $i, $res[3]);
            $sheet->setCellValue('E' . $i, $res[4]);
            $sheet->setCellValue('F' . $i, $res[5]);
            $sheet->setCellValue('G' . $i, $res[6]);
            $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
            $sheet->setCellValue('I' . $i, dias_permiso($res['RFC'], $descuentos));
            $sheet->setCellValue('J' . $i, mb_strtoupper(strftime("DEL %d DE %B DE %G", strtotime($res[7])) . strftime(" AL %d DE %B DE %G", strtotime($res[8]))));
            $sheet->setCellValue('K' . $i, $res[9]);
            $sheet->setCellValue('L' . $i, mb_strtoupper($res[10]));
            $i++;
        }
    }

    $spreadsheet->getActiveSheet()->getStyle('A3:L' . $i)->applyFromArray($contenido);
    $i = $i + 5;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':L' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "_____________________________________");
    $i++;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':L' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
    foreach (range('A', 'L') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    #------------------------------------------------------------------------------------------

    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(6);
    $sheet = $spreadsheet->getActiveSheet()->setTitle('Honorarios y Eventuales');
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:K1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'HONORARIOS Y EVENTUALES' . $texto);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:K2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
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

    $sql = "SELECT * FROM Usuario WHERE categoria = 'user' AND tipoTrabajador IN('HONORARIOS','EVENTUAL') AND STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "' ORDER BY departamento";
    $consulta = mysqli_query($conexion, $sql);
    $i = 3;
    $departamentos = [];
    if ($consulta && (mysqli_num_rows($consulta) > 0)) {
        $spreadsheet->getActiveSheet()->getStyle('A3:K3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ffffff');
        while ($res = mysqli_fetch_array($consulta)) {
            $bandera = false;
            if ($res[7] === 'baja') {
                $sql1 = "SELECT RFC FROM Baja WHERE fecha >= '" . $del . "' AND fecha <= '" . $al . "' AND RFC = '" . $res[8] . "'";
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
                $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($res['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $i, mb_strtoupper($res[6]));
                $sheet->setCellValue('C' . $i, $res[9]);
                $sheet->setCellValue('D' . $i, $res[8]);
                $sheet->setCellValue('E' . $i, $res[11]);
                $sheet->setCellValue('F' . $i, $res[12]);
                $sheet->setCellValue('G' . $i, $res[10]);
                $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
                $sheet->setCellValue('I' . $i, $res['tipoTrabajador']);
                $sheet->setCellValue('J' . $i, mb_strtoupper($res[7]));
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
                array_push($departamentos, $res[12]);
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
        $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($color);
        $i++;
    }

    $i = $i + 5;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':J' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "_____________________________________");
    $i++;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':J' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $spreadsheet->getActiveSheet()->setAutoFilter('A2:J2');



    #------------------------------------------------------------------------------------------
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(7);
    $sheet = $spreadsheet->getActiveSheet()->setTitle('Prenomina');
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:K1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'PRENOMINA' . $texto);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:K2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
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

    $sql = "SELECT * FROM Usuario WHERE 
    id_periodo =  ".$periodo." AND 
    categoria = 'user' AND 
    tipoTrabajador NOT IN('EVENTUAL','HONORARIOS') AND 
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "' 
    ORDER BY departamento";
    $consulta = mysqli_query($conexion, $sql);
    $i = 3;
    $departamentos = [];
    if ($consulta && (mysqli_num_rows($consulta) > 0)) {
        $spreadsheet->getActiveSheet()->getStyle('A3:K3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ffffff');
        while ($res = mysqli_fetch_array($consulta)) {
            $bandera = false;
            if ($res[7] === 'baja') {
                $sql1 = "SELECT RFC FROM Baja WHERE fecha >= '" . $del . "' AND fecha <= '" . $al . "' AND RFC = '" . $res[8] . "'";
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
                $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($res['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $i, mb_strtoupper($res[6]));
                $sheet->setCellValue('C' . $i, $res[9]);
                $sheet->setCellValue('D' . $i, $res[8]);
                $sheet->setCellValue('E' . $i, $res[11]);
                $sheet->setCellValue('F' . $i, $res[12]);
                $sheet->setCellValue('G' . $i, $res[10]);
                $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));
                $sheet->setCellValue('I' . $i, $res['tipoTrabajador']);
                $sheet->setCellValue('J' . $i, mb_strtoupper($res[7]));
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
                array_push($departamentos, $res[12]);
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
        $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($color);
        $i++;
    }

    $i = $i + 5;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':J' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "_____________________________________");
    $i++;
    $spreadsheet->getActiveSheet()->mergeCells('A' . $i . ':J' . $i);
    $spreadsheet->getActiveSheet()->getStyle("A" . $i)->applyFromArray($firma);
    $sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $spreadsheet->getActiveSheet()->setAutoFilter('A2:J2');


    #------------------------------------------------------------------------------------------

    $file = uniqid() . ".xlsx";
    $ruta = $ruta . $file;
    $url = 'assets/prenominas/' . $file;

    $sql = "INSERT INTO Prenomina(del, al, id_periodo, observacion, url) VALUES(
        STR_TO_DATE('" . $del_original . "','%d/%m/%Y'),
        STR_TO_DATE('" . $al_original . "','%d/%m/%Y'),
        " . $periodo . ",
        '" . $observacion . "',
        '" . $url . "'
    )";

    if (mysqli_query($conexion, $sql)) {
        $writer = new Xlsx($spreadsheet);
        $writer->save($ruta);

        echo $url;
    } else {
        echo 0;
    }
} else {
    echo 0;
}

mysqli_close($conexion);
exit();
