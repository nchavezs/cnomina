<?php
session_start();
setlocale(LC_ALL, "spanish");
require '../../vendor/autoload.php';
include "conexion.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conexion = conexion();
$observacion = trim($_POST["observacion"]) ?: 'Sin observaciones';
$periodo = $_SESSION["id_periodo"];
$ano = $_SESSION["ano"];

// ----------------------------------------------------------------------------------------------------------------------------
function alta($id, $baja = null)
{
    $conexion = conexion();

    if ($baja) {
        $sql = "SELECT MAX(inicio) as maxima FROM Reingreso WHERE fecha >= " . $baja . " AND RFC = '" . $id . "'";
        $resultado = $conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        $fecha = $fila['maxima'];
    } else {
        $sql = "SELECT MAX(fecha) AS maxima FROM Historial WHERE tipo = 'reingreso' AND RFC = '" . $id . "'";
        $resultado = $conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        $fecha = $fila['maxima'];
    }

    if ($fecha) {
        return date("d/m/Y", strtotime($fecha));
    } else {
        $sql = "SELECT fechaRelLab FROM Empleado WHERE RFC = '" . $id . "'";
        $resultado = $conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['fechaRelLab'];
    }
}

function cabecera($titulo, $col, $sheet)
{
    $sheet->mergeCells('A1:B1');
    $sheet->mergeCells('C1:' . $col . '1');
    $sheet->getStyle("C1")->applyFromArray($GLOBALS["titulos"]);
    $sheet->setCellValue('C1', $titulo);
    logo($sheet);
    $sheet->getRowDimension('1')->setRowHeight(40);
    $sheet->getStyle('A2:' . $col . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $sheet->getStyle('A2:' . $col . '2')->getFont()->getColor()->setRGB('FFFFFF');
}

function firma($i, $col, $sheet)
{
    $sheet->getStyle('A3:' . $col . $i)->applyFromArray($GLOBALS["contenido"]);
    $i = $i + 5;
    $sheet->mergeCells('A' . $i . ':' . $col . $i);
    $sheet->getStyle("A" . $i)->applyFromArray($GLOBALS["firma"]);
    $sheet->setCellValue('A' . $i, "_____________________________________");
    $i++;
    $sheet->mergeCells('A' . $i . ':' . $col . $i);
    $sheet->getStyle("A" . $i)->applyFromArray($GLOBALS["firma"]);
    $sheet->setCellValue('A' . $i, "DIRECTOR DE RECURSOS HUMANOS");
    foreach (range('A', $col) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
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

function dias_movimiento($res, $fecha_del, $fecha_al, $conexion)
{
    $rfc = $res["RFC"];
    $id_prenomina = $res["id_prenomina"];
    $plaza_nueva = $res['plaza'];
    $plaza_vieja = $res['plazaAnterior'];

    // 2. Obtener Fechas de las Plazas
    $sql_nueva = "SELECT fecha_inicio, fecha_fin FROM Historial_Plaza WHERE RFC = '$rfc' AND id_plaza = $plaza_nueva ORDER BY elaboracion DESC LIMIT 1";
    $result_nueva = $conexion->query($sql_nueva);
    $row_nueva = mysqli_fetch_assoc($result_nueva);

    $fecha_inicio_nueva = $row_nueva['fecha_inicio'] ? date("Y-m-d", strtotime($row_nueva['fecha_inicio'])) : null;
    $fecha_fin_nueva = $row_nueva['fecha_fin'] ? date("Y-m-d", strtotime($row_nueva['fecha_fin'])) : null;

    $sql_vieja = "SELECT fecha_inicio, fecha_fin FROM Historial_Plaza WHERE RFC = '$rfc' AND id_plaza = $plaza_vieja ORDER BY elaboracion DESC LIMIT 1";
    $result_vieja = $conexion->query($sql_vieja);
    $row_vieja = mysqli_fetch_assoc($result_vieja);

    $fecha_inicio_vieja = $row_vieja['fecha_inicio'] ? date("Y-m-d", strtotime($row_vieja['fecha_inicio'])) : null;
    $fecha_fin_vieja = $row_vieja['fecha_fin'] ? date("Y-m-d", strtotime($row_vieja['fecha_fin'])) : null;

    // 3. Ajustar Fechas según $del
    if ($fecha_inicio_nueva < $fecha_del) {
        $fecha_inicio_nueva = $fecha_del;
    }
    if ($fecha_fin_nueva === null || $fecha_fin_nueva > $fecha_al) {
        $fecha_fin_nueva = $fecha_al;
    }

    if ($fecha_inicio_vieja < $fecha_del) {
        $fecha_inicio_vieja = $fecha_del;
    }

    if ($fecha_fin_vieja < $fecha_inicio_vieja) {
        $dias_pago_vieja = 0;
    } else {
        $dias_pago_vieja = diferencia($fecha_inicio_vieja, $fecha_fin_vieja);
    }
    // 4. Calcular Días de Pago desde Historial_Plaza
    $dias_pago_nueva = diferencia($fecha_inicio_nueva, $fecha_fin_nueva);

    // 5. Obtener y Restar Días Descontados
    $descuentos_nuevo = 0;
    $descuentos_viejo = 0;

    $sql_descuento = "SELECT fechas FROM Descuento WHERE RFC = '" . $rfc . "' AND id_prenomina = $id_prenomina";
    $result_descuento = $conexion->query($sql_descuento);

    while ($row_descuento = mysqli_fetch_assoc($result_descuento)) {
        $fechas_descuento = explode(',', $row_descuento['fechas']);

        foreach ($fechas_descuento as $fecha) {
            $fecha_trimmed = str_replace("/", "-", trim($fecha));
            $fecha_desc = date("Y-m-d", strtotime($fecha_trimmed));

            // Comprobar si cae dentro de la plaza nueva
            if ($fecha_desc >= $fecha_inicio_nueva && $fecha_desc <= $fecha_fin_nueva) {
                $descuentos_nuevo++;
            }

            // Comprobar si cae dentro de la plaza vieja
            if ($fecha_desc >= $fecha_inicio_vieja && $fecha_desc <= $fecha_fin_vieja) {
                $descuentos_viejo++;
            }
        }
    }

    // Obtener días de Licencia
    $licencias_nuevo = 0;
    $licencias_viejo = 0;

    $sql_licencia = "SELECT del, al FROM Permiso WHERE categoria = 1 AND RFC = '$rfc' AND id_prenomina = $id_prenomina";
    $result_licencia = $conexion->query($sql_licencia);

    while ($row_licencia = mysqli_fetch_assoc($result_licencia)) {
        $fecha_inicio_licencia = date("Y-m-d", strtotime($row_licencia['del']));
        $fecha_fin_licencia = date("Y-m-d", strtotime($row_licencia['al']));
       
        // Calcular días descontados por licencia
        if ($fecha_inicio_licencia <= $fecha_fin_nueva && $fecha_fin_licencia >= $fecha_inicio_nueva) {
            $licencias_nuevo += diferencia(min($fecha_fin_nueva, $fecha_fin_licencia), max($fecha_inicio_nueva, $fecha_inicio_licencia));
            
        }

        if ($fecha_inicio_licencia <= $fecha_fin_vieja && $fecha_fin_licencia >= $fecha_inicio_vieja) {
            $licencias_viejo += diferencia(min($fecha_fin_vieja, $fecha_fin_licencia), max($fecha_inicio_vieja, $fecha_inicio_licencia));
        }

    }

    // Total de días pagados
    $descontados = $descuentos_viejo + $licencias_viejo;
    $dias_pago_vieja = ($dias_pago_vieja - $descontados) < 0 ? 0 : $dias_pago_vieja - $descontados;
    $descontados = $descuentos_nuevo + $licencias_nuevo;
    $dias_pago_nueva = ($dias_pago_nueva - $descontados) < 0 ? 0 : $dias_pago_nueva - $descontados;

    return [$dias_pago_vieja, $dias_pago_nueva];
}

function diferencia($fecha1, $fecha2)
{
    list($ano1, $mes1, $dia1) = explode('-', $fecha1);
    list($ano2, $mes2, $dia2) = explode('-', $fecha2);

    // Convierte todo a un total de días considerando 30 días por mes
    $diasTotales1 = ($ano1 * 12 * 30) + (($mes1 - 1) * 30) + ($dia1 - 1);
    $diasTotales2 = ($ano2 * 12 * 30) + (($mes2 - 1) * 30) + ($dia2 - 1);

    // Calcula la diferencia de días y suma 1 para incluir ambos extremos
    $diferencia = abs($diasTotales2 - $diasTotales1) + 1;

    return $diferencia;
}

// ----------------------------------------------------------------------------------------------------------------------------
$ruta = './../prenominas/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$titulos = [
    'font' => [
        'size' => 16,
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
// ----------------------------------------------------------------------------------------------------------------------------

$sql = "SELECT *,
    (SELECT dias FROM Periodo WHERE id_periodo = Prenomina.id_periodo) AS dias
    FROM Prenomina WHERE
    YEAR(del) = " . $ano . " AND
    id_periodo = " . $periodo . "
    ORDER BY id_prenomina DESC LIMIT 1";

$consulta = $conexion->query($sql);
$prenomina = mysqli_fetch_array($consulta);
$id_prenomina = $prenomina["id_prenomina"];
$del = $prenomina["del"];
$al = $prenomina["al"];
$dias = $prenomina["dias"];

$descuentos = [];
$titulo = mb_strtoupper(strftime(" del %e de %B", strtotime($del)) . strftime(" al %e de %B", strtotime($al)) . strftime(" del %Y", strtotime($del)));
$del = date("Y-m-d", strtotime(str_replace('/', '-', $del)));
$al = date("Y-m-d", strtotime(str_replace('/', '-', $al)));

$dias_pago = diferencia($del, $al);
$dias_pago = ($dias_pago > $dias) ? $dias : $dias_pago;

$sql = "SELECT *,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT estado FROM Usuario WHERE RFC = Empleado.RFC) AS estado,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
    (SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS tipoTrabajador
    FROM Empleado WHERE
    id_periodo = " . $periodo . " AND
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'";

$query = $conexion->query($sql);

while ($usuario = mysqli_fetch_array($query)) {
    $fecha_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $usuario["fechaRelLab"])));
    if ($usuario["estado"] == 'alta') {
        if (($fecha_inicio >= $del) && ($fecha_inicio <= $al)) {
            $paga = diferencia($fecha_inicio, $al);
        } else {
            $sql = "SELECT * FROM Historial WHERE
            RFC = '" . $usuario["RFC"] . "' AND
            tipo = 'alta' AND
            id_prenomina = " . $id_prenomina;

            $consulta = $conexion->query($sql);
            $paga = $dias_pago;
            if ($consulta && mysqli_num_rows($consulta) > 0) {
                $alta = mysqli_fetch_array($consulta);
                if ($alta["retroactivo"] == 1) {
                    $paga = diferencia($fecha_inicio, $al);
                }
            }
        }

        $sql = "SELECT * FROM Historial WHERE
            RFC = '" . $usuario["RFC"] . "' AND
            id_prenomina = " . $id_prenomina . " AND
            tipo = 'reingreso' ORDER BY fecha DESC LIMIT 1";

        $consulta = $conexion->query($sql);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $reingreso = mysqli_fetch_array($consulta);
            $fecha_reingreso = $reingreso["fecha"];
            $paga = diferencia($fecha_reingreso, $al);

            $sql = "SELECT * FROM Historial WHERE
            RFC = '" . $usuario["RFC"] . "' AND
            id_prenomina = " . $id_prenomina . " AND
            tipo = 'baja' ORDER BY fecha DESC LIMIT 1";

            $consulta = $conexion->query($sql);
            if ($consulta && mysqli_num_rows($consulta) > 0) {
                $baja = mysqli_fetch_array($consulta);

                if ($baja["retroactivo"] == 1) {
                    $paga = 0;
                } else {
                    $fecha_baja = $baja["fecha"];

                    if (($fecha_baja >= $del) && ($fecha_inicio <= $al) && ($fecha_inicio >= $del)) {
                        $paga = $paga + diferencia($fecha_inicio, $fecha_baja);
                    } else {
                        $paga = $paga + diferencia($del, $fecha_baja);
                    }
                }
            }

        }

    } else {
        $sql = "SELECT * FROM Historial WHERE
        RFC = '" . $usuario["RFC"] . "' AND
        id_prenomina = " . $prenomina["id_prenomina"] . " AND
        tipo = 'baja' ORDER BY fecha DESC LIMIT 1";

        $consulta = $conexion->query($sql);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $baja = mysqli_fetch_array($consulta);
            if ($baja["retroactivo"] == 1) {
                $paga = 0;
            } else {
                $fecha_baja = $baja["fecha"];

                if (($fecha_baja >= $del) && ($fecha_inicio <= $al) && ($fecha_inicio >= $del)) {
                    $paga = diferencia($fecha_inicio, $fecha_baja);
                } else {
                    $paga = diferencia($del, $fecha_baja);
                }
            }
        }
    }

    $datos = [];
    $descontados = 0;
    $descontados_permiso = 0;

    $sql = "SELECT * FROM Descuento WHERE RFC = '" . $usuario["RFC"] . "' AND id_prenomina = " . $id_prenomina;
    $consulta = $conexion->query($sql);
    if ($consulta && mysqli_num_rows($consulta) > 0) {
        while ($descuento = mysqli_fetch_array($consulta)) {
            $descontados = $descontados + $descuento["dias"];
        }
    }

    $sql = "SELECT * FROM Permiso WHERE RFC = '" . $usuario["RFC"] . "' AND categoria = 1 AND id_prenomina = " . $id_prenomina;
    $consulta = $conexion->query($sql);
    if ($consulta && mysqli_num_rows($consulta) > 0) {
        while ($permiso = mysqli_fetch_array($consulta)) {
            $descontados_permiso = $descontados_permiso + $permiso["dias"];
        }
    }

    $paga = $paga - ($descontados + $descontados_permiso);

    // ESTA CONDICION CREO QUE NO ES NECESARIA AHORA CON LO DE DIFF > DIAS
    if ($periodo == 2) {
        $diff = diferencia($del, $al);
        if ($diff != 30) {
            $paga = $paga + (30 - $diff);
        }
    }

    if ($paga < 0) {
        $paga = 0;
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

$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0);

// MOVIMIENTOS ---------------------------------------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Movimientos');
$spreadsheet->addSheet($sheet);
$col = "J";
cabecera("MOVIMIENTOS" . $titulo, $col, $sheet);
$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'RFC');
$sheet->setCellValue('D2', 'FECHA DE MOVIMIENTO');
$sheet->setCellValue('E2', 'PUESTO ACTUAL');
$sheet->setCellValue('F2', 'DEPARTAMENTO ACTUAL');
$sheet->setCellValue('G2', 'DIAS A PAGAR (PUESTO ACTUAL)');
$sheet->setCellValue('H2', 'PUESTO ANTERIOR');
$sheet->setCellValue('I2', 'DEPARTAMENTO ANTERIOR');
$sheet->setCellValue('J2', 'DIAS A PAGAR (PUESTO ANTERIOR)');

$sql = "SELECT *,
(SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre
FROM Movimiento LEFT JOIN Empleado ON Movimiento.RFC = Empleado.RFC WHERE
id_prenomina = " . $id_prenomina;

$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {

    while ($res = mysqli_fetch_array($consulta)) {
        $pagas = dias_movimiento($res, $del, $al, $conexion);
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["RFC"]);
        $sheet->setCellValue('D' . $i, date("d/m/Y", strtotime($res["fecha"])));
        $sheet->setCellValue('E' . $i, $res["puesto"]);
        $sheet->setCellValue('F' . $i, $res["departamento"]);
        $sheet->setCellValue('G' . $i, $pagas[1]);
        $sheet->setCellValue('H' . $i, $res["puestoAnterior"]);
        $sheet->setCellValue('I' . $i, $res["departamentoAnterior"]);
        $sheet->setCellValue('J' . $i, $pagas[0]);

        $i++;
    }
}
firma($i, $col, $sheet);

// BAJAS ---------------------------------------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Bajas');
$spreadsheet->addSheet($sheet);
$col = "I";
cabecera("BAJAS" . $titulo, $col, $sheet);

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'RFC');
$sheet->setCellValue('D2', 'PUESTO');
$sheet->setCellValue('E2', 'DEPARTAMENTO');
$sheet->setCellValue('F2', 'FECHA DE INGRESO');
$sheet->setCellValue('G2', 'FECHA DE BAJA');
$sheet->setCellValue('H2', 'OBSERVACIONES');
$sheet->setCellValue('I2', 'DIAS A PAGAR');

$sql = "SELECT Historial.*,
Empleado.fechaRelLab,
Empleado.id_empleado,
(SELECT razon FROM Baja WHERE RFC = Historial.RFC AND fecha = Historial.fecha) AS razon,
(SELECT nombre FROM Usuario WHERE RFC = Historial.RFC) AS nombre,
(SELECT nombre FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Plaza WHERE id_plaza = (SELECT id_plaza FROM Baja WHERE RFC = Historial.RFC AND fecha = Historial.fecha LIMIT 1))) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Plaza WHERE id_plaza = (SELECT id_plaza FROM Baja WHERE RFC = Historial.RFC AND fecha = Historial.fecha LIMIT 1)))) AS departamento
FROM Historial LEFT JOIN Empleado ON Historial.RFC = Empleado.RFC WHERE id_prenomina = " . $id_prenomina . " AND tipo = 'baja'";

$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["RFC"]);
        $sheet->setCellValue('D' . $i, $res["puesto"]);
        $sheet->setCellValue('E' . $i, $res["departamento"]);
        $sheet->setCellValue('F' . $i, alta($res['RFC'], $res["fecha"]));
        $sheet->setCellValue('G' . $i, date("d/m/Y", strtotime($res["fecha"])));
        $sheet->setCellValue('H' . $i, mb_strtoupper($res["razon"]));
        $sheet->setCellValue('I' . $i, dias_paga($res['RFC'], $descuentos));
        $i++;
    }
}

firma($i, $col, $sheet);

// REINGRESO ---------------------------------------------------------------------------------------------------------------------------
$sql = "SELECT *,
(SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
(SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
FROM Empleado LEFT JOIN Historial ON Empleado.RFC = Historial.RFC WHERE
tipo = 'reingreso' AND
id_prenomina = " . $id_prenomina;
$consulta = $conexion->query($sql);

if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Reingreso');
    $spreadsheet->addSheet($sheet);
    $col = "H";
    cabecera("REINGRESO" . $titulo, $col, $sheet);

    $sheet->setCellValue('A2', "# EMPLEADO");
    $sheet->setCellValue('B2', 'NOMBRE');
    $sheet->setCellValue('C2', 'RFC');
    $sheet->setCellValue('D2', 'PUESTO');
    $sheet->setCellValue('E2', 'DEPARTAMENTO');
    $sheet->setCellValue('F2', 'FECHA DE REINGRESO');
    $sheet->setCellValue('G2', 'OBSERVACIONES');
    $sheet->setCellValue('H2', 'DIAS A PAGAR');

    $i = 3;

    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["RFC"]);
        $sheet->setCellValue('D' . $i, $res["puesto"]);
        $sheet->setCellValue('E' . $i, $res["departamento"]);
        $sheet->setCellValue('F' . $i, date("d/m/Y", strtotime($res["fecha"])));
        $sheet->setCellValue('G' . $i, $res["descripcion"]);
        $sheet->setCellValue('H' . $i, dias_paga($res['RFC'], $descuentos));

        $i++;
    }
}

firma($i, $col, $sheet);

// ALTAS ---------------------------------------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Altas');
$spreadsheet->addSheet($sheet);
$col = "G";
cabecera("ALTAS" . $titulo, $col, $sheet);

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'RFC');
$sheet->setCellValue('D2', 'PUESTO');
$sheet->setCellValue('E2', 'DEPARTAMENTO');
$sheet->setCellValue('F2', 'FECHA DE INGRESO');
$sheet->setCellValue('G2', 'DIAS A PAGAR');

$sql = "SELECT *,
(SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
(SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
FROM Empleado LEFT JOIN Historial ON Empleado.RFC = Historial.RFC WHERE
tipo = 'alta' AND
id_prenomina = " . $id_prenomina;
$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["RFC"]);
        $sheet->setCellValue('D' . $i, $res["puesto"]);
        $sheet->setCellValue('E' . $i, $res["departamento"]);
        $sheet->setCellValue('F' . $i, alta($res['RFC']));
        $sheet->setCellValue('G' . $i, dias_paga($res['RFC'], $descuentos));

        $i++;
    }
}

firma($i, $col, $sheet);

// DESCUENTOS ---------------------------------------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Descuentos');
$spreadsheet->addSheet($sheet);
$col = "I";
cabecera("DESCUENTOS" . $titulo, $col, $sheet);
$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'RFC');
$sheet->setCellValue('D2', 'PUESTO');
$sheet->setCellValue('E2', 'DEPARTAMENTO');
$sheet->setCellValue('F2', 'FECHA DE DESCUENTO');
$sheet->setCellValue('G2', 'DIAS DESCONTADOS');
$sheet->setCellValue('H2', 'MOTIVO');
$sheet->setCellValue('I2', 'DIAS A PAGAR');

$sql = "SELECT *,
(SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
(SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
FROM Descuento LEFT JOIN Empleado ON Descuento.RFC = Empleado.RFC WHERE
id_prenomina =" . $id_prenomina;

$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["RFC"]);
        $sheet->setCellValue('D' . $i, $res["puesto"]);
        $sheet->setCellValue('E' . $i, $res["departamento"]);
        $sheet->setCellValue('F' . $i, $res["fechas"]);
        $sheet->setCellValue('G' . $i, $res["dias"]);
        $sheet->setCellValue('H' . $i, mb_strtoupper($res["motivo"]));
        $sheet->setCellValue('I' . $i, dias_paga($res['RFC'], $descuentos));
        $i++;
    }
}

firma($i, $col, $sheet);

// CON GOCE ---------------------------------------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Licencias con goce');
$spreadsheet->addSheet($sheet);
$col = "I";
cabecera("LICENCIAS CON GOCE" . $titulo, $col, $sheet);

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'RFC');
$sheet->setCellValue('D2', 'PUESTO');
$sheet->setCellValue('E2', 'DEPARTAMENTO');
$sheet->setCellValue('F2', 'FECHA DE LICENCIA');
$sheet->setCellValue('G2', 'DIAS DE LICENCIA');
$sheet->setCellValue('H2', 'OBSERVACIONES');
$sheet->setCellValue('I2', 'DIAS A PAGAR');

$sql = "SELECT *,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
    FROM Permiso LEFT JOIN Empleado ON Permiso.RFC = Empleado.RFC WHERE
    id_prenomina = " . $id_prenomina . " AND
    Permiso.categoria = 0
    ORDER BY Permiso.RFC ASC";

$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["RFC"]);
        $sheet->setCellValue('D' . $i, $res["puesto"]);
        $sheet->setCellValue('E' . $i, $res["departamento"]);
        $sheet->setCellValue('F' . $i, mb_strtoupper(strftime("DEL %d DE %B DE %G", strtotime($res["del"])) . strftime(" AL %d DE %B DE %G", strtotime($res["al"]))));
        $sheet->setCellValue('G' . $i, $res["dias"]);
        $sheet->setCellValue('H' . $i, mb_strtoupper($res["descripcion"]));
        $sheet->setCellValue('I' . $i, dias_paga($res['RFC'], $descuentos));

        $i++;
    }
}

firma($i, $col, $sheet);

// SIN GOCE ---------------------------------------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Licencias sin goce');
$spreadsheet->addSheet($sheet);
$col = "I";
cabecera("LICENCIAS SIN GOCE" . $titulo, $col, $sheet);

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'RFC');
$sheet->setCellValue('D2', 'PUESTO');
$sheet->setCellValue('E2', 'DEPARTAMENTO');
$sheet->setCellValue('F2', 'FECHA DE LICENCIA');
$sheet->setCellValue('G2', 'DIAS DESCONTADOS');
$sheet->setCellValue('H2', 'OBSERVACIONES');
$sheet->setCellValue('I2', 'DIAS A PAGAR');

$sql = "SELECT *,
    (SELECT estado FROM Usuario WHERE RFC = Empleado.RFC) AS estado,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
    FROM Permiso LEFT JOIN Empleado ON Permiso.RFC = Empleado.RFC WHERE
    id_prenomina =  " . $id_prenomina . " AND
    Permiso.categoria = 1
    ORDER BY Permiso.RFC ASC";
$consulta = $conexion->query($sql);

$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["RFC"]);
        $sheet->setCellValue('D' . $i, $res["puesto"]);
        $sheet->setCellValue('E' . $i, $res["departamento"]);
        $sheet->setCellValue('F' . $i, mb_strtoupper(strftime("DEL %d DE %B DE %G", strtotime($res["del"])) . strftime(" AL %d DE %B DE %G", strtotime($res["al"]))));
        $sheet->setCellValue('G' . $i, $res["dias"]);
        $sheet->setCellValue('H' . $i, mb_strtoupper($res["descripcion"]));
        $sheet->setCellValue('I' . $i, dias_paga($res['RFC'], $descuentos));

        $i++;
    }
}

firma($i, $col, $sheet);

// PASES ---------------------------------------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Pases de entrada y salida');
$spreadsheet->addSheet($sheet);
$col = "G";
cabecera("PASES DE ENTRADA Y SALIDA" . $titulo, $col, $sheet);

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'RFC');
$sheet->setCellValue('D2', 'TIPO DE PASE');
$sheet->setCellValue('E2', 'FECHA');
$sheet->setCellValue('F2', 'HORA');
$sheet->setCellValue('G2', 'OBSERVACIONES');

$sql = "SELECT *,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre
    FROM Pase LEFT JOIN Empleado ON Pase.RFC = Empleado.RFC WHERE
    id_prenomina = " . $id_prenomina . "
    ORDER BY Pase.RFC ASC";

$consulta = $conexion->query($sql);
$i = 3;
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    while ($res = mysqli_fetch_array($consulta)) {
        $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
        $sheet->setCellValue('C' . $i, $res["RFC"]);
        $sheet->setCellValue('D' . $i, $res["categoria"] == 0 ? "PASE DE ENTRADA" : "PASE DE SALIDA");
        $sheet->setCellValue('E' . $i, date("d/m/Y", strtotime($res["fecha"])));
        $sheet->setCellValue('F' . $i, $res["hora"]);
        $sheet->setCellValue('G' . $i, mb_strtoupper($res["observacion"]));
        $i++;
    }
}

firma($i, $col, $sheet);

// HONORARIOS ---------------------------------------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Honorarios y Eventuales');
$spreadsheet->addSheet($sheet);
$col = "K";
cabecera("HONORARIOS Y EVENTUALES" . $titulo, $col, $sheet);

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'RFC');
$sheet->setCellValue('D2', 'PUESTO');
$sheet->setCellValue('E2', 'DEPARTAMENTO');
$sheet->setCellValue('F2', 'FECHA DE INGRESO');
$sheet->setCellValue('G2', 'CATEGORIA');
$sheet->setCellValue('H2', 'ESTADO DEL EMPLEADO');
$sheet->setCellValue('I2', 'OBSERVACIONES');
$sheet->setCellValue('J2', 'DIAS A PAGAR');
$sheet->setCellValue('K2', 'DIAS DESCONTADOS');

$sql = "SELECT *,
    (SELECT estado FROM Usuario WHERE RFC = Empleado.RFC) AS estado,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
    (SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS tipoTrabajador
    FROM Empleado LEFT JOIN Puesto ON Puesto.id_puesto = Empleado.id_puesto WHERE
    id_periodo =  " . $periodo . " AND
    id_trabajador IN(3,4) AND
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'
    ORDER BY departamento";

$consulta = $conexion->query($sql);
$i = 3;
$departamentos = [];
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $sheet->getStyle('A3:' . $col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ffffff');
    while ($res = mysqli_fetch_array($consulta)) {
        $bandera = false;
        if ($res["estado"] == 'baja') {
            $sql = "SELECT * FROM Historial WHERE
                tipo = 'baja' AND
                RFC = '" . $res['RFC'] . "' AND
                id_prenomina = " . $id_prenomina;

            $consulta1 = $conexion->query($sql);
            if ($consulta1 && (mysqli_num_rows($consulta1) > 0)) {
                $bandera = true;
            }
        } else {
            $bandera = true;
        }

        if ($bandera) {
            $sql = "SELECT * FROM Historial WHERE
                tipo = 'reingreso' AND
                RFC = '" . $res['RFC'] . "' AND
                id_prenomina = " . $id_prenomina;

            $consulta1 = $conexion->query($sql);
            $observaciones = "";
            if ($consulta1 && mysqli_num_rows($consulta1) > 0) {
                $observaciones = "REINGRESO";
            }
            $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
            $sheet->setCellValue('C' . $i, $res["RFC"]);
            $sheet->setCellValue('D' . $i, $res["puesto"]);
            $sheet->setCellValue('E' . $i, $res["departamento"]);
            $sheet->setCellValue('F' . $i, alta($res['RFC']));
            $sheet->setCellValue('G' . $i, $res['tipoTrabajador']);
            $sheet->setCellValue('H' . $i, mb_strtoupper($res["estado"]));
            $sheet->setCellValue('I' . $i, $observaciones);
            $sheet->setCellValue('J' . $i, dias_paga($res['RFC'], $descuentos));
            $sheet->setCellValue('K' . $i, dias_descontados($res['RFC'], $descuentos) + dias_permiso($res['RFC'], $descuentos));

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
    $sheet->getStyle('A' . $i . ':' . $col . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($color);
    $i++;
}

firma($i, $col, $sheet);
// SEGURIDAD PUBLICA ---------------------------------------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Seguridad Pública');
$spreadsheet->addSheet($sheet);
$col = "K";
cabecera("SEGURIDAD PÚBLICA" . $titulo, $col, $sheet);

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'RFC');
$sheet->setCellValue('D2', 'PUESTO');
$sheet->setCellValue('E2', 'DEPARTAMENTO');
$sheet->setCellValue('F2', 'FECHA DE INGRESO');
$sheet->setCellValue('G2', 'CATEGORIA');
$sheet->setCellValue('H2', 'ESTADO DEL EMPLEADO');
$sheet->setCellValue('I2', 'OBSERVACIONES');
$sheet->setCellValue('J2', 'DIAS A PAGAR');
$sheet->setCellValue('K2', 'DIAS DESCONTADOS');

$sql = "SELECT *,
    (SELECT estado FROM Usuario WHERE RFC = Empleado.RFC) AS estado,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
    (SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS tipoTrabajador
    FROM Empleado LEFT JOIN Puesto ON Puesto.id_puesto = Empleado.id_puesto WHERE
    id_periodo =  " . $periodo . " AND
    id_departamento = 21 AND
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'
    ORDER BY departamento";

$consulta = $conexion->query($sql);
$i = 3;
$departamentos = [];
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $sheet->getStyle('A3:' . $col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ffffff');
    while ($res = mysqli_fetch_array($consulta)) {
        $bandera = false;
        if ($res["estado"] == 'baja') {
            $sql = "SELECT * FROM Historial WHERE
                tipo = 'baja' AND
                RFC = '" . $res['RFC'] . "' AND
                id_prenomina = " . $id_prenomina;

            $consulta1 = $conexion->query($sql);
            if ($consulta1 && (mysqli_num_rows($consulta1) > 0)) {
                $bandera = true;
            }
        } else {
            $bandera = true;
        }

        if ($bandera) {
            $sql = "SELECT * FROM Historial WHERE
                tipo = 'reingreso' AND
                RFC = '" . $res['RFC'] . "' AND
                id_prenomina = " . $id_prenomina;

            $consulta1 = $conexion->query($sql);
            $observaciones = "";
            if ($consulta1 && mysqli_num_rows($consulta1) > 0) {
                $observaciones = "REINGRESO";
            }
            $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
            $sheet->setCellValue('C' . $i, $res["RFC"]);
            $sheet->setCellValue('D' . $i, $res["puesto"]);
            $sheet->setCellValue('E' . $i, $res["departamento"]);
            $sheet->setCellValue('F' . $i, alta($res['RFC']));
            $sheet->setCellValue('G' . $i, $res['tipoTrabajador']);
            $sheet->setCellValue('H' . $i, mb_strtoupper($res["estado"]));
            $sheet->setCellValue('I' . $i, $observaciones);
            $sheet->setCellValue('J' . $i, dias_paga($res['RFC'], $descuentos));
            $sheet->setCellValue('K' . $i, dias_descontados($res['RFC'], $descuentos) + dias_permiso($res['RFC'], $descuentos));

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
    $sheet->getStyle('A' . $i . ':' . $col . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($color);
    $i++;
}

firma($i, $col, $sheet);

// PRENOMINA ---------------------------------------------------------------------------------------------------------------------------
$sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Prenomina');
$spreadsheet->addSheet($sheet);
$col = "K";
cabecera("PRENOMINA" . $titulo, $col, $sheet);

$sheet->setCellValue('A2', "# EMPLEADO");
$sheet->setCellValue('B2', 'NOMBRE');
$sheet->setCellValue('C2', 'RFC');
$sheet->setCellValue('D2', 'PUESTO');
$sheet->setCellValue('E2', 'DEPARTAMENTO');
$sheet->setCellValue('F2', 'FECHA DE INGRESO');
$sheet->setCellValue('G2', 'CATEGORIA');
$sheet->setCellValue('H2', 'ESTADO DEL EMPLEADO');
$sheet->setCellValue('I2', 'OBSERVACIONES');
$sheet->setCellValue('J2', 'DIAS A PAGAR');
$sheet->setCellValue('K2', 'DIAS DESCONTADOS');

$sql = "SELECT *,
    (SELECT estado FROM Usuario WHERE RFC = Empleado.RFC) AS estado,
    (SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
    (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
    (SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS tipoTrabajador
    FROM Empleado LEFT JOIN Puesto ON Puesto.id_puesto = Empleado.id_puesto WHERE
    id_periodo =  " . $periodo . " AND
    id_trabajador NOT IN(3,4) AND
    id_departamento != 21 AND
    STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $al . "'
    ORDER BY departamento";

$consulta = $conexion->query($sql);
$i = 3;
$departamentos = [];
if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    $sheet->getStyle('A3:' . $col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ffffff');
    while ($res = mysqli_fetch_array($consulta)) {
        $bandera = false;
        if ($res["estado"] == 'baja') {
            $sql = "SELECT * FROM Historial WHERE
            tipo = 'baja' AND
            RFC = '" . $res['RFC'] . "' AND
            id_prenomina = " . $id_prenomina;

            $consulta1 = $conexion->query($sql);
            if ($consulta1 && (mysqli_num_rows($consulta1) > 0)) {
                $bandera = true;
            }
        } else {
            $bandera = true;
        }

        if ($bandera) {
            $sql = "SELECT * FROM Historial WHERE
            tipo = 'reingreso' AND
            RFC = '" . $res['RFC'] . "' AND
            id_prenomina = " . $id_prenomina;

            $consulta1 = $conexion->query($sql);
            $observaciones = "";
            if ($consulta1 && mysqli_num_rows($consulta1) > 0) {
                $observaciones = "REINGRESO";
            }
            $sheet->getCell('A' . $i)->setValueExplicit(str_pad($res['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($res["nombre"]));
            $sheet->setCellValue('C' . $i, $res["RFC"]);
            $sheet->setCellValue('D' . $i, $res["puesto"]);
            $sheet->setCellValue('E' . $i, $res["departamento"]);
            $sheet->setCellValue('F' . $i, alta($res['RFC']));
            $sheet->setCellValue('G' . $i, $res['tipoTrabajador']);
            $sheet->setCellValue('H' . $i, mb_strtoupper($res["estado"]));
            $sheet->setCellValue('I' . $i, $observaciones);
            $sheet->setCellValue('J' . $i, dias_paga($res['RFC'], $descuentos));
            $sheet->setCellValue('K' . $i, dias_descontados($res['RFC'], $descuentos) + dias_permiso($res['RFC'], $descuentos));

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
    $sheet->getStyle('A' . $i . ':' . $col . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($color);
    $i++;
}

firma($i, $col, $sheet);

// --------------------------------------------------------------------------------------------------------------------------------

$url = "Prenomina_" . date("d_m_Y_H_i") . ".xlsx";
$ruta = $ruta . $url;

$sql = "UPDATE Prenomina SET observacion = '" . $observacion . "', url = '" . $url . "' WHERE id_prenomina = " . $id_prenomina;

if ($conexion->query($sql)) {
    $writer = new Xlsx($spreadsheet);
    $writer->save($ruta);

    echo $url;
} else {
    echo 0;
}

$conexion->close();
exit();