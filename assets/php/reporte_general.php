<?php
include "conexion.php";
include "municipio.php";

require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
setlocale(LC_ALL, "spanish");

function logo($sheet)
{
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($sheet);
}

$conexion = conexion();
$hoy = date('d/m/Y', time());

$bandera = false;
$sin_goce = $_POST["sin_goce"];
$con_goce = $_POST["con_goce"];
$pases = $_POST["pases"];
$movimientos = $_POST["movimientos"];
$vacaciones = $_POST["vacaciones"];
$descuentos = $_POST["descuentos"];
$medicos = $_POST["medicos"];
$altas = $_POST["altas"];
$bajas = $_POST["bajas"];

$del = $_POST["del"];
$al = $_POST["al"];

$puestos = $_POST["puestos"];
$departamentos = $_POST["departamentos"];

if ($del == "" || $al == "") {
    echo "Perido de fecha incorrecta.";
} else {
    if (sizeof($puestos) > 0) {
        $array_puestos = implode(",", $puestos);
        $extra = " AND Empleado.id_puesto IN (" . $array_puestos . ") ";
    }

    $titulos = [
        'font' => [
            'size' => 14,
            "bold" => true
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

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $del)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $al)));

    $del_letra = mb_strtoupper(strftime("%d de %B del %G", strtotime($date1)));
    $al_letra = mb_strtoupper(strftime("%d de %B de %G", strtotime($date2)));

    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0);

// ----------------- MOVIMIENTOS --------------------
    if ($movimientos == 1) {
        $sql = "SELECT
        Movimiento.*,
        Empleado.id_empleado,
        Empleado.nombres,
        Empleado.apellidom,
        Empleado.apellidop,
        Empleado.CURP,
        Empleado.fechaRelLab
        FROM Movimiento LEFT JOIN Empleado ON Movimiento.RFC = Empleado.RFC WHERE
        fecha >= '" . $date1 . "' AND
        fecha <= '" . $date2 . "'
        " . $extra . "
        ORDER BY Movimiento.RFC";

        $consulta = $conexion->query($sql);

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Movimientos');
        $spreadsheet->addSheet($sheet);
        logo($sheet);
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:N1');
        $sheet->getStyle("C1")->applyFromArray($titulos);
        $sheet->setCellValue('C1', "MUNICIPIO DE ".get_municipio()."\n".'REPORTE DE MOVIMIENTOS DEL ' . $del_letra . ' AL ' . $al_letra );
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth("50");
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
        $sheet->getStyle('A2:N2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('A2', '# EMPLEADO');
        $sheet->setCellValue('B2', 'NOMBRE');
        $sheet->setCellValue('C2', 'APELLIDO PATERNO');
        $sheet->setCellValue('D2', 'APELLIDO MATERNO');
        $sheet->setCellValue('E2', 'CURP');
        $sheet->setCellValue('F2', 'RFC');
        $sheet->setCellValue('G2', 'FECHA DE INGRESO');
        $sheet->setCellValue('H2', 'PUESTO ACTUAL');
        $sheet->setCellValue('I2', 'PUESTO ANTERIOR');
        $sheet->setCellValue('J2', 'DEPARTAMENTO ACTUAL');
        $sheet->setCellValue('K2', 'DEPARTAMENTO ANTERIOR');
        $sheet->setCellValue('L2', 'FECHA DE MOVIMIENTO');
        $sheet->setCellValue('M2', 'FECHA DE REGISTRO');
        $sheet->setCellValue('N2', 'OBSERVACIONES');

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $i = 3;
            while ($resultado = mysqli_fetch_array($consulta)) {
                $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
                $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
                $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
                $sheet->setCellValue('E' . $i, $resultado['CURP']);
                $sheet->setCellValue('F' . $i, $resultado['RFC']);
                $sheet->setCellValue('G' . $i, $resultado['fechaRelLab']);
                $sheet->setCellValue('H' . $i, $resultado['puesto']);
                $sheet->setCellValue('I' . $i, $resultado['puestoAnterior']);
                $sheet->setCellValue('J' . $i, $resultado['departamento']);
                $sheet->setCellValue('K' . $i, $resultado['departamentoAnterior']);
                $sheet->setCellValue('L' . $i, date("d/m/Y", strtotime($resultado['fecha'])));
                $sheet->setCellValue('M' . $i, date("d/m/Y H:i", strtotime($resultado['elaboracion'])));
                $sheet->setCellValue('N' . $i, $resultado['observacion']);

                $i++;
            }
            $spreadsheet->getActiveSheet()->getStyle('A3:N' . $i)->applyFromArray($contenido);
            foreach (range('A', 'N') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }
    }

// ----------------- DESCUENTOS --------------------
    if ($descuentos == 1) {
        $sql = "SELECT
        Descuento.*,
        Empleado.id_empleado,
        Empleado.nombres,
        Empleado.apellidom,
        Empleado.apellidop,
        Empleado.CURP,
        Empleado.fechaRelLab,
        (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
        (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
        FROM Descuento LEFT JOIN Empleado ON Descuento.RFC = Empleado.RFC WHERE
        Descuento.id_descuento >= 0 " . $extra . ' ORDER BY Descuento.RFC';

        $consulta = $conexion->query($sql);

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Descuentos');
        $spreadsheet->addSheet($sheet);
        logo($sheet);
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:L1');
        $sheet->getStyle("C1")->applyFromArray($titulos);
        $sheet->setCellValue('C1', "MUNICIPIO DE ".get_municipio()."\n".'REPORTE DE DESCUENTOS DEL ' . $del_letra . ' AL ' . $al_letra );
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth("50");
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('A2:L2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
        $sheet->getStyle('A2:L2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('A2', '# EMPLEADO');
        $sheet->setCellValue('B2', 'NOMBRE');
        $sheet->setCellValue('C2', 'APELLIDO PATERNO');
        $sheet->setCellValue('D2', 'APELLIDO MATERNO');
        $sheet->setCellValue('E2', 'CURP');
        $sheet->setCellValue('F2', 'RFC');
        $sheet->setCellValue('G2', 'PUESTO');
        $sheet->setCellValue('H2', 'DEPARTAMENTO');
        $sheet->setCellValue('I2', 'FECHA DE INGRESO');
        $sheet->setCellValue('J2', 'FECHA DE REGISTRO');
        $sheet->setCellValue('K2', 'FECHA DE DESCUENTO');
        $sheet->setCellValue('L2', 'OBSERVACIONES');

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $i = 3;
            while ($resultado = mysqli_fetch_array($consulta)) {
                $fechas = explode(",", $resultado['fechas']);
                foreach ($fechas as $fecha) {
                    $date = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));
                    if ($date >= $date1 && $date <= $date2) {
                        $bandera = true;
                        $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
                        $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
                        $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
                        $sheet->setCellValue('E' . $i, $resultado['CURP']);
                        $sheet->setCellValue('F' . $i, $resultado['RFC']);
                        $sheet->setCellValue('G' . $i, $resultado['puesto']);
                        $sheet->setCellValue('H' . $i, $resultado['departamento']);
                        $sheet->setCellValue('I' . $i, $resultado['fechaRelLab']);
                        $sheet->setCellValue('J' . $i, date("d/m/Y H:i", strtotime($resultado["elaboracion"])));
                        $sheet->setCellValue('K' . $i, $fecha);
                        $sheet->setCellValue('L' . $i, $resultado['motivo']);
                        $i++;
                    }
                }
            }
            $spreadsheet->getActiveSheet()->getStyle('A3:L' . $i)->applyFromArray($contenido);
            foreach (range('A', 'L') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }
    }

// ----------------- VACACIONES --------------------
    if ($vacaciones == 1) {
        $sql = "SELECT
        Vacacion.*,
        Empleado.id_empleado,
        Empleado.nombres,
        Empleado.apellidom,
        Empleado.apellidop,
        Empleado.CURP,
        Empleado.fechaRelLab,
        (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
        (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
        FROM Vacacion LEFT JOIN Empleado ON Vacacion.RFC = Empleado.RFC WHERE 
        -- al >= '" . $date1 . "' AND
        -- al <= '" . $date2 . "'
        (del BETWEEN '".$date1."' AND '".$date2."' OR al BETWEEN '".$date1."' AND '".$date2."') 
        " . $extra . " ORDER BY Vacacion.RFC";

        $consulta = $conexion->query($sql);
        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Vacaciones');
        $spreadsheet->addSheet($sheet);
        logo($sheet);
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:N1');
        $sheet->getStyle("C1")->applyFromArray($titulos);
        $sheet->setCellValue('C1', "MUNICIPIO DE ".get_municipio()."\n".'REPORTE DE VACACIONES DEL ' . $del_letra . ' AL ' . $al_letra);
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth("50");
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
        $sheet->getStyle('A2:N2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('A2', '# EMPLEADO');
        $sheet->setCellValue('B2', 'NOMBRE');
        $sheet->setCellValue('C2', 'APELLIDO PATERNO');
        $sheet->setCellValue('D2', 'APELLIDO MATERNO');
        $sheet->setCellValue('E2', 'CURP');
        $sheet->setCellValue('F2', 'RFC');
        $sheet->setCellValue('G2', 'FECHA DE INGRESO');
        $sheet->setCellValue('H2', 'PUESTO');
        $sheet->setCellValue('I2', 'DEPARTAMENTO');
        $sheet->setCellValue('J2', 'PERIODO DEL');
        $sheet->setCellValue('K2', 'PERIODO AL');
        $sheet->setCellValue('L2', 'FECHA DE REGISTRO');
        $sheet->setCellValue('M2', 'DIAS DE VACACIONES');
        $sheet->setCellValue('N2', 'OBSERVACIONES');

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $i = 3;

            while ($resultado = mysqli_fetch_array($consulta)) {
                $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
                $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
                $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
                $sheet->setCellValue('E' . $i, $resultado['CURP']);
                $sheet->setCellValue('F' . $i, $resultado['RFC']);
                $sheet->setCellValue('G' . $i, $resultado['fechaRelLab']);
                $sheet->setCellValue('H' . $i, $resultado['puesto']);
                $sheet->setCellValue('I' . $i, $resultado['departamento']);
                $sheet->setCellValue('J' . $i, date("d/m/Y", strtotime($resultado['del'])));
                $sheet->setCellValue('K' . $i, date("d/m/Y", strtotime($resultado['al'])));
                $sheet->setCellValue('L' . $i, date("d/m/Y H:i", strtotime($resultado["elaboracion"])));
                $sheet->setCellValue('M' . $i, $resultado['dias']);
                $sheet->setCellValue('N' . $i, $resultado['descripcion']);

                $i++;
            }

            $spreadsheet->getActiveSheet()->getStyle('A3:N' . $i)->applyFromArray($contenido);
            foreach (range('A', 'N') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }
    }

// ----------------- PERMISOS CON GOCE --------------------
    if ($con_goce == 1) {
        $sql = "SELECT
        Permiso.*,
        Empleado.id_empleado,
        Empleado.nombres,
        Empleado.apellidom,
        Empleado.apellidop,
        Empleado.CURP,
        Empleado.fechaRelLab,
        (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
        (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
        FROM Permiso LEFT JOIN Empleado ON Permiso.RFC = Empleado.RFC WHERE 
        (del BETWEEN '".$date1."' AND '".$date2."' OR al BETWEEN '".$date1."' AND '".$date2."') AND
        Permiso.categoria = 0
        " . $extra . " 
        ORDER BY Permiso.RFC";
        $consulta = $conexion->query($sql);

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Permisos con goce');
        $spreadsheet->addSheet($sheet);
        logo($sheet);
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:O1');
        $sheet->getStyle("C1")->applyFromArray($titulos);
        $sheet->setCellValue('C1', "MUNICIPIO DE ".get_municipio()."\n".'REPORTE DE PERMISOS CON GOCE DE SUELDO DEL ' . $del_letra . ' AL ' . $al_letra);
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth("50");
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('A2:O2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
        $sheet->getStyle('A2:O2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('A2', '# EMPLEADO');
        $sheet->setCellValue('B2', 'NOMBRE');
        $sheet->setCellValue('C2', 'APELLIDO PATERNO');
        $sheet->setCellValue('D2', 'APELLIDO MATERNO');
        $sheet->setCellValue('E2', 'CURP');
        $sheet->setCellValue('F2', 'RFC');
        $sheet->setCellValue('G2', 'FECHA DE INGRESO');
        $sheet->setCellValue('H2', 'PUESTO');
        $sheet->setCellValue('I2', 'DEPARTAMENTO');
        $sheet->setCellValue('J2', 'PERMISO DEL');
        $sheet->setCellValue('K2', 'PERMISO AL');
        $sheet->setCellValue('L2', 'DIAS DE PERMISO');
        $sheet->setCellValue('M2', 'MATERNIDAD');
        $sheet->setCellValue('N2', 'OBSERVACIONES');
        $sheet->setCellValue('O2', 'FECHA DE REGISTRO');

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $i = 3;

            while ($resultado = mysqli_fetch_array($consulta)) {
                $materno = "NO";
                if ($resultado['materno'] == 0) {
                    $materno = "SI";
                }

                $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
                $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
                $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
                $sheet->setCellValue('E' . $i, $resultado['CURP']);
                $sheet->setCellValue('F' . $i, $resultado['RFC']);
                $sheet->setCellValue('G' . $i, $resultado['fechaRelLab']);
                $sheet->setCellValue('H' . $i, $resultado['puesto']);
                $sheet->setCellValue('I' . $i, $resultado['departamento']);
                $sheet->setCellValue('J' . $i, date("d/m/Y", strtotime($resultado['del'])));
                $sheet->setCellValue('K' . $i, date("d/m/Y", strtotime($resultado['al'])));
                $sheet->setCellValue('L' . $i, $resultado['dias']);
                $sheet->setCellValue('M' . $i, $materno);
                $sheet->setCellValue('N' . $i, $resultado['descripcion']);
                $sheet->setCellValue('O' . $i, date("d/m/Y H:i", strtotime($resultado["elaboracion"])));

                $i++;
            }

            $spreadsheet->getActiveSheet()->getStyle('A3:O' . $i)->applyFromArray($contenido);
            foreach (range('A', 'O') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }
    }

// ----------------- PERMISOS SIN GOCE --------------------
    if ($sin_goce == 1) {
        $sql = "SELECT
        Permiso.*,
        Empleado.id_empleado,
        Empleado.nombres,
        Empleado.apellidom,
        Empleado.apellidop,
        Empleado.CURP,
        Empleado.fechaRelLab,
        (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
        (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
        FROM Permiso LEFT JOIN Empleado ON Permiso.RFC = Empleado.RFC WHERE
        (del BETWEEN '".$date1."' AND '".$date2."' OR al BETWEEN '".$date1."' AND '".$date2."') AND
        Permiso.categoria = 1
        " . $extra . " 
        ORDER BY Permiso.RFC";
        $consulta = $conexion->query($sql);

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Permisos sin goce');
        $spreadsheet->addSheet($sheet);
        logo($sheet);
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:N1');
        $sheet->getStyle("C1")->applyFromArray($titulos);
        $sheet->setCellValue('C1', "MUNICIPIO DE ".get_municipio()."\n".'REPORTE DE PERMISOS SIN GOCE DE SUELDO DEL ' . $del_letra . ' AL ' . $al_letra );
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth("50");
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
        $sheet->getStyle('A2:N2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('A2', '# EMPLEADO');
        $sheet->setCellValue('B2', 'NOMBRE');
        $sheet->setCellValue('C2', 'APELLIDO PATERNO');
        $sheet->setCellValue('D2', 'APELLIDO MATERNO');
        $sheet->setCellValue('E2', 'CURP');
        $sheet->setCellValue('F2', 'RFC');
        $sheet->setCellValue('G2', 'FECHA DE INGRESO');
        $sheet->setCellValue('H2', 'PUESTO');
        $sheet->setCellValue('I2', 'DEPARTAMENTO');
        $sheet->setCellValue('J2', 'PERMISO DEL');
        $sheet->setCellValue('K2', 'PERMISO AL');
        $sheet->setCellValue('L2', 'DIAS DE PERMISO');
        $sheet->setCellValue('M2', 'OBSERVACIONES');
        $sheet->setCellValue('N2', 'FECHA DE REGISTRO');

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $i = 3;

            while ($resultado = mysqli_fetch_array($consulta)) {
                $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
                $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
                $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
                $sheet->setCellValue('E' . $i, $resultado['CURP']);
                $sheet->setCellValue('F' . $i, $resultado['RFC']);
                $sheet->setCellValue('G' . $i, $resultado['fechaRelLab']);
                $sheet->setCellValue('H' . $i, $resultado['puesto']);
                $sheet->setCellValue('I' . $i, $resultado['departamento']);
                $sheet->setCellValue('J' . $i, date("d/m/Y", strtotime($resultado['del'])));
                $sheet->setCellValue('K' . $i, date("d/m/Y", strtotime($resultado['al'])));
                $sheet->setCellValue('L' . $i, $resultado['dias']);
                $sheet->setCellValue('M' . $i, $resultado['descripcion']);
                $sheet->setCellValue('N' . $i, date("d/m/Y H:i", strtotime($resultado["elaboracion"])));
                $i++;
            }

            $spreadsheet->getActiveSheet()->getStyle('A3:N' . $i)->applyFromArray($contenido);
            foreach (range('A', 'N') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }
    }

// ----------------- ALTAS --------------------
    if ($altas == 1) {
        $sql = "SELECT *,
        (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
        (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
        (SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS tipoTrabajador 
        FROM Empleado";
        $consulta = $conexion->query($sql);

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Altas');
        $spreadsheet->addSheet($sheet);
        logo($sheet);
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:N1');
        $sheet->getStyle("C1")->applyFromArray($titulos);
        $sheet->setCellValue('C1', "MUNICIPIO DE ".get_municipio()."\n".'REPORTE DE ALTAS DEL ' . $del_letra . ' AL ' . $al_letra);
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth("50");
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
        $sheet->getStyle('A2:N2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('A2', '# EMPLEADO');
        $sheet->setCellValue('B2', 'NOMBRE');
        $sheet->setCellValue('C2', 'APELLIDO PATERNO');
        $sheet->setCellValue('D2', 'APELLIDO MATERNO');
        $sheet->setCellValue('E2', 'CURP');
        $sheet->setCellValue('F2', 'RFC');
        $sheet->setCellValue('G2', 'FECHA DE INGRESO');
        $sheet->setCellValue('H2', 'PUESTO');
        $sheet->setCellValue('I2', 'DEPARTAMENTO');
        $sheet->setCellValue('J2', 'CUENTA BANCARIA');
        $sheet->setCellValue('K2', 'NO. DE AFILIACIÓN');
        $sheet->setCellValue('L2', 'TIPO DE TRABAJADOR');
        $sheet->setCellValue('M2', 'FECHA DE REGISTRO');
        $sheet->setCellValue('N2', 'OBSERVACIONES');

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $i = 3;
            while ($resultado = mysqli_fetch_array($consulta)) {
                $sql0 = "SELECT * FROM Reingreso WHERE RFC = '" . $resultado['RFC'] . "'";
                $consulta0 = mysqli_query($conexion, $sql0);
                $observaciones = "";
                if ($consulta0 && mysqli_num_rows($consulta0) > 0) {
                    $observaciones = "REINGRESO";
                }

                $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
                $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
                $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
                $sheet->setCellValue('E' . $i, $resultado['CURP']);
                $sheet->setCellValue('F' . $i, $resultado['RFC']);
                $sheet->setCellValue('G' . $i, $resultado['fechaRelLab']);
                $sheet->setCellValue('H' . $i, $resultado['puesto']);
                $sheet->setCellValue('I' . $i, $resultado['departamento']);
                $sheet->setCellValue('J' . $i, $resultado['banca']);
                $sheet->setCellValue('K' . $i, $resultado['afiliacion']);
                $sheet->setCellValue('L' . $i, $resultado['tipoTrabajador']);
                $sheet->setCellValue('M' . $i, date("d/m/Y H:i", strtotime($resultado['elaboracion'])));
                $sheet->setCellValue('N' . $i, $observaciones);
                $i++;
            }
            $spreadsheet->getActiveSheet()->getStyle('A3:N' . $i)->applyFromArray($contenido);
            foreach (range('A', 'N') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }
    }

// ----------------- BAJAS --------------------
    if ($bajas == 1) {
        $sql = "SELECT
        Baja.*,
        Empleado.id_empleado,
        Empleado.nombres,
        Empleado.apellidom,
        Empleado.apellidop,
        Empleado.CURP,
        Empleado.fechaRelLab,
        (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
        (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
        FROM Baja LEFT JOIN Empleado ON Baja.RFC = Empleado.RFC WHERE
        fecha >= '" . $date1 . "' AND
        fecha <= '" . $date2 . "'
        " . $extra . "
        ORDER BY Baja.RFC";
        $consulta = $conexion->query($sql);

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Bajas');
        $spreadsheet->addSheet($sheet);
        logo($sheet);
        $sheet->mergeCells('C1:L1');
        $sheet->getStyle("C1")->applyFromArray($titulos);
        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('C1', "MUNICIPIO DE ".get_municipio()."\n".'REPORTE DE BAJAS DEL ' . $del_letra . ' AL ' . $al_letra );
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth("50");
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('A2:L2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
        $sheet->getStyle('A2:L2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('A2', '# EMPLEADO');
        $sheet->setCellValue('B2', 'NOMBRE');
        $sheet->setCellValue('C2', 'APELLIDO PATERNO');
        $sheet->setCellValue('D2', 'APELLIDO MATERNO');
        $sheet->setCellValue('E2', 'CURP');
        $sheet->setCellValue('F2', 'RFC');
        $sheet->setCellValue('G2', 'FECHA DE INGRESO');
        $sheet->setCellValue('H2', 'PUESTO');
        $sheet->setCellValue('I2', 'DEPARTAMENTO');
        $sheet->setCellValue('J2', 'FECHA DE BAJA');
        $sheet->setCellValue('K2', 'FECHA DE REGISTRO');
        $sheet->setCellValue('L2', 'OBSERVACIONES');

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $i = 3;
            while ($resultado = mysqli_fetch_array($consulta)) {
                $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
                $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
                $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
                $sheet->setCellValue('E' . $i, $resultado['CURP']);
                $sheet->setCellValue('F' . $i, $resultado['RFC']);
                $sheet->setCellValue('G' . $i, $resultado['fechaRelLab']);
                $sheet->setCellValue('H' . $i, $resultado['puesto']);
                $sheet->setCellValue('I' . $i, $resultado['departamento']);
                $sheet->setCellValue('J' . $i, date("d/m/Y", strtotime($resultado['fecha'])));
                $sheet->setCellValue('K' . $i, date("d/m/Y H:i", strtotime($resultado["elaboracion"])));
                $sheet->setCellValue('L' . $i, $resultado['razon']);
                $i++;
            }
            $spreadsheet->getActiveSheet()->getStyle('A3:L' . $i)->applyFromArray($contenido);
            foreach (range('A', 'L') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }
    }
// ----------------- BENEFICIARIOS --------------------
    // if ($beneficiarios) {
    //     $sql = "SELECT
    //     Beneficiario.*,
    //     Usuario.nombre
    //     FROM Beneficiario LEFT JOIN Usuario ON Beneficiario.RFC = Usuario.RFC";

    //     $consulta = $conexion->query($sql);

    //     $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Beneficiarios');
    //     $spreadsheet->addSheet($sheet);
    //     logo($sheet);
    //     $sheet->mergeCells('B1:D1');
    //     $sheet->getStyle("B1")->applyFromArray($titulos);
    //     $sheet->setCellValue('B1', 'LISTA DE BENEFICIARIOS');
    //     $sheet->getRowDimension('1')->setRowHeight(40);
    //     $sheet->getStyle('A2:D2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    //     $sheet->getStyle('A2:D2')->getFont()->getColor()->setRGB('FFFFFF');
    //     $sheet->setCellValue('A2', 'NOMBRE DEL USUARIO');
    //     $sheet->setCellValue('B2', 'NOMBRE DEL BENEFICIARIO');
    //     $sheet->setCellValue('C2', 'PARENTESCO');
    //     $sheet->setCellValue('D2', 'FECHA DE REGISTRO');

    //     if ($consulta && mysqli_num_rows($consulta) > 0 && $beneficiarios == 1) {
    //         $bandera = true;
    //         $i = 3;
    //         while ($resultado = mysqli_fetch_array($consulta)) {
    //             $sheet->setCellValue('A' . $i, mb_strtoupper($resultado['nombre']));
    //             $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['beneficiario']));
    //             $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['parentesco']));
    //             $sheet->setCellValue('D' . $i, date("d/m/Y H:i", strtotime($resultado["elaboracion"])));
    //             $i++;
    //         }
    //         $spreadsheet->getActiveSheet()->getStyle('A3:D' . $i)->applyFromArray($contenido);
    //         foreach (range('A', 'D') as $columnID) {
    //             $sheet->getColumnDimension($columnID)->setAutoSize(true);
    //         }
    //     }
    // }

// ----------------- PASES --------------------
    if ($pases == 1) {
        $sql = "SELECT
        Pase.*,
        Empleado.id_empleado,
        Empleado.nombres,
        Empleado.apellidom,
        Empleado.apellidop,
        Empleado.CURP,
        Empleado.fechaRelLab,
        (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
        (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento
        FROM Pase LEFT JOIN Empleado ON Pase.RFC = Empleado.RFC WHERE
        fecha >= '" . $date1 . "' AND
        fecha <= '" . $date2 . "'
        " . $extra . "
        ORDER BY Pase.RFC";
        $consulta = $conexion->query($sql);

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Pases');
        $spreadsheet->addSheet($sheet);
        logo($sheet);
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:N1');
        $sheet->getStyle("C1")->applyFromArray($titulos);
        $sheet->setCellValue('C1', "MUNICIPIO DE ".get_municipio()."\n".'REPORTE DE PASES DEL ' . $del_letra . ' AL ' . $al_letra );
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth("50");
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
        $sheet->getStyle('A2:N2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('A2', '# EMPLEADO');
        $sheet->setCellValue('B2', 'NOMBRE');
        $sheet->setCellValue('C2', 'APELLIDO PATERNO');
        $sheet->setCellValue('D2', 'APELLIDO MATERNO');
        $sheet->setCellValue('E2', 'CURP');
        $sheet->setCellValue('F2', 'RFC');
        $sheet->setCellValue('G2', 'FECHA DE INGRESO');
        $sheet->setCellValue('H2', 'PUESTO');
        $sheet->setCellValue('I2', 'DEPARTAMENTO');
        $sheet->setCellValue('J2', 'FECHA DE PASE');
        $sheet->setCellValue('K2', 'HORA DE PASE');
        $sheet->setCellValue('L2', 'CATEGORÍA');
        $sheet->setCellValue('M2', 'FECHA DE REGISTRO');
        $sheet->setCellValue('N2', 'OBSERVACIONES');

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $i = 3;

            while ($resultado = mysqli_fetch_array($consulta)) {
                $categoria = 'ENTRADA';
                if ($resultado[4] == 1) {
                    $categoria = 'SALIDA';
                }

                $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_empleado'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
                $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
                $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
                $sheet->setCellValue('E' . $i, $resultado['CURP']);
                $sheet->setCellValue('F' . $i, $resultado['RFC']);
                $sheet->setCellValue('G' . $i, $resultado['fechaRelLab']);
                $sheet->setCellValue('H' . $i, $resultado['puesto']);
                $sheet->setCellValue('I' . $i, $resultado['departamento']);
                $sheet->setCellValue('J' . $i, date("d/m/Y", strtotime($resultado['fecha'])));
                $sheet->setCellValue('K' . $i, $resultado['hora']);
                $sheet->setCellValue('L' . $i, $categoria);
                $sheet->setCellValue('M' . $i, date("d/m/Y H:i", strtotime($resultado["elaboracion"])));
                $sheet->setCellValue('N' . $i, $resultado['observacion']);
                $i++;
            }
            $spreadsheet->getActiveSheet()->getStyle('A3:N' . $i)->applyFromArray($contenido);
            foreach (range('A', 'N') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }
    }

    if ($bandera) {
        $nombre = "reporte_".time().".xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save('../archivos/'.$nombre);
        echo "assets/archivos/".$nombre;
    } else {
        echo "No se encontraron resultados.";
    }

    $conexion->close();
}
