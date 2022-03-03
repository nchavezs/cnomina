<?php
include "conexion.php";
$conexion = conexion();

require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

setlocale(LC_ALL, "spanish");
$hoy = date('d/m/Y', time());

$bandera = false;
$fecha1 = $_POST["fecha1"];
$fecha2 = $_POST["fecha2"];
$fecha3 = $_POST["fecha3"];
$fecha4 = $_POST["fecha4"];
$fecha5 = $_POST["fecha5"];
$fecha6 = $_POST["fecha6"];
$fecha7 = $_POST["fecha7"];
$fecha8 = $_POST["fecha8"];
$fecha9 = $_POST["fecha9"];
$fecha10 = $_POST["fecha10"];
$fecha13 = $_POST["fecha13"];
$fecha14 = $_POST["fecha14"];
$fecha15 = $_POST["fecha15"];
$fecha16 = $_POST["fecha16"];
$fecha17 = $_POST["fecha17"];
$fecha18 = $_POST["fecha18"];
$puesto = trim($_POST["puesto"]);
$departamento = trim($_POST["departamento"]);
$beneficiarios = $_POST["beneficiarios"];

if ($fecha1 == 0 && $fecha3 == 0 && $fecha5 == 0 && $fecha7 == 0 && $fecha9 == 0 && $fecha13 == 0 && $fecha15 == 0 && $fecha17 == 0 && $beneficiarios == 0) {
    echo 0;
} else {
    if ($puesto !== "") {
        $puesto = " AND Usuario.puesto = '" . $puesto . "' ";
    }

    if ($departamento !== "") {
        $departamento = " AND Usuario.departamento = '" . $departamento . "' ";
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

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha1)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha2)));
    $sql = "SELECT * FROM Movimiento INNER JOIN Usuario ON Movimiento.RFC = Usuario.RFC WHERE fecha >= '" . $date1 . "' AND fecha <= '" . $date2 . "'" . $puesto . $departamento.' ORDER BY Movimiento.RFC';
    $consulta = mysqli_query($conexion, $sql);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet()->setTitle("Movimientos");
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:N1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'MOVIMIENTOS DEL ' . $fecha1 . ' AL ' . $fecha2);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFont()->getColor()->setRGB('FFFFFF');
    $sheet->setCellValue('A2', 'ID');
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
            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
            $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
            $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
            $sheet->setCellValue('E' . $i, $resultado['CURP']);
            $sheet->setCellValue('F' . $i, $resultado['RFC']);
            $sheet->setCellValue('G' . $i, $resultado['fechaRelLab']);
            $sheet->setCellValue('H' . $i, $resultado[3]);
            $sheet->setCellValue('I' . $i, $resultado[6]);
            $sheet->setCellValue('J' . $i, $resultado[4]);
            $sheet->setCellValue('K' . $i, $resultado[7]);
            $sheet->setCellValue('L' . $i, date("d/m/Y", strtotime($resultado['fecha'])));
            $sheet->setCellValue('M' . $i, date("d/m/Y H:i", strtotime($resultado[9])));
            $sheet->setCellValue('N' . $i, $resultado['observacion']);

            $i++;
        }
        $spreadsheet->getActiveSheet()->getStyle('A3:N' . $i)->applyFromArray($contenido);
        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    } else {
        $fecha1 = 0;
    }

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha3)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha4)));
    $sql = "SELECT * FROM Descuento INNER JOIN Usuario ON Descuento.RFC = Usuario.RFC WHERE Descuento.id_descuento >= 0 ". $puesto . $departamento.' ORDER BY Descuento.RFC';
    $consulta = mysqli_query($conexion, $sql);
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(1);
    $sheet = $spreadsheet->getActiveSheet()->setTitle("Descuentos");
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:L1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'DESCUENTOS DEL ' . $fecha3 . ' AL ' . $fecha4);
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
                    $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
                    $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
                    $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
                    $sheet->setCellValue('E' . $i, $resultado['CURP']);
                    $sheet->setCellValue('F' . $i, $resultado['RFC']);
                    $sheet->setCellValue('G' . $i, $resultado['puesto']);
                    $sheet->setCellValue('H' . $i, $resultado['departamento']);
                    $sheet->setCellValue('I' . $i, $resultado['fechaRelLab']);
                    $sheet->setCellValue('J' . $i, date("d/m/Y H:i", strtotime($resultado[7])));
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
    } else {
        $fecha3 = 0;
    }

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha5)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha6)));
    $sql = "SELECT * FROM Vacacion INNER JOIN Usuario ON Vacacion.RFC = Usuario.RFC WHERE al >= '" . $date1 . "' AND al <= '" . $date2 . "'" . $puesto . $departamento .' ORDER BY Vacacion.RFC';

    $consulta = mysqli_query($conexion, $sql);
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(2);
    $sheet = $spreadsheet->getActiveSheet()->setTitle("Vacaciones");
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:N1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'VACACIONES DEL ' . $fecha5 . ' AL ' . $fecha6);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
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
            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
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
            $sheet->setCellValue('L' . $i, date("d/m/Y H:i", strtotime($resultado[8])));
            $sheet->setCellValue('M' . $i, $resultado['dias']);
            $sheet->setCellValue('N' . $i, $resultado['descripcion']);

            $i++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A3:N' . $i)->applyFromArray($contenido);
        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    } else {
        $fecha5 = 0;
    }

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha7)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha8)));
    $sql = "SELECT * FROM Permiso INNER JOIN Usuario ON Permiso.RFC = Usuario.RFC WHERE al >= '" . $date1 . "' AND al <= '" . $date2 . "' AND Permiso.categoria = 0" . $puesto . $departamento.' ORDER BY Permiso.RFC';
    $consulta = mysqli_query($conexion, $sql);
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(3);
    $sheet = $spreadsheet->getActiveSheet()->setTitle("Permisos con goce");
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:O1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'PERMISOS CON GOCE DE SUELDO DEL ' . $fecha7 . ' AL ' . $fecha8);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:O2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:O2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
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
    $sheet->setCellValue('M2', 'DIAS DE PERMISO');
    $sheet->setCellValue('N2', 'MATERNIDAD');
    $sheet->setCellValue('O2', 'OBSERVACIONES');
    if ($consulta && mysqli_num_rows($consulta) > 0) {
        $bandera = true;
        $i = 3;

        while ($resultado = mysqli_fetch_array($consulta)) {
            $materno = "NO";
            if ($resultado['materno'] == 0) {
                $materno = "SI";
            }

            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
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
            $sheet->setCellValue('L' . $i, date("d/m/Y H:i", strtotime($resultado[10])));
            $sheet->setCellValue('M' . $i, $resultado['dias']);
            $sheet->setCellValue('N' . $i, $materno);
            $sheet->setCellValue('O' . $i, $resultado['descripcion']);

            $i++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A3:O' . $i)->applyFromArray($contenido);
        foreach (range('A', 'O') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    } else {
        $fecha7 = 0;
    }

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha9)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha10)));
    $sql = "SELECT * FROM Permiso INNER JOIN Usuario ON Permiso.RFC = Usuario.RFC WHERE al >= '" . $date1 . "' AND al <= '" . $date2 . "' AND Permiso.categoria = 1" . $puesto . $departamento.' ORDER BY Permiso.RFC';
    $consulta = mysqli_query($conexion, $sql);
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(4);
    $sheet = $spreadsheet->getActiveSheet()->setTitle("Permisos sin goce");
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:N1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'PERMISOS SIN GOCE DE SUELDO DEL ' . $fecha9 . ' AL ' . $fecha10);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFont()->getColor()->setRGB('FFFFFF');

    $sheet->setCellValue('A2', 'ID');
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
    $sheet->setCellValue('M2', 'DIAS DE PERMISO');
    $sheet->setCellValue('N2', 'OBSERVACIONES');
    if ($consulta && mysqli_num_rows($consulta) > 0) {
        $bandera = true;
        $i = 3;

        while ($resultado = mysqli_fetch_array($consulta)) {
            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
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
            $sheet->setCellValue('L' . $i, date("d/m/Y H:i", strtotime($resultado[10])));
            $sheet->setCellValue('M' . $i, $resultado['dias']);
            $sheet->setCellValue('N' . $i, $resultado['descripcion']);
            $i++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A3:N' . $i)->applyFromArray($contenido);
        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    } else {
        $fecha9 = 0;
    }

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha13)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha14)));
    $sql = "SELECT * FROM Usuario WHERE STR_TO_DATE(fechaRelLab,'%d/%m/%Y') >= '" . $date1 . "' AND STR_TO_DATE(fechaRelLab,'%d/%m/%Y') <= '" . $date2 . "'" . $puesto . $departamento.' ORDER BY Usuario.RFC';
    $consulta = mysqli_query($conexion, $sql);
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(5);
    $sheet = $spreadsheet->getActiveSheet()->setTitle("Altas");
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:N1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'ALTAS DEL ' . $fecha13 . ' AL ' . $fecha14);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFont()->getColor()->setRGB('FFFFFF');
    $sheet->setCellValue('A2', 'ID');
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
            $sql0 = "SELECT * FROM Reingreso WHERE RFC = '".$resultado['RFC']."'";
            $consulta0 = mysqli_query($conexion, $sql0);
            $observaciones = "";
            if($consulta0 && mysqli_num_rows($consulta0) > 0){
                $observaciones = "REINGRESO";
            }

            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
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
    } else {
        $fecha13 = 0;
    }

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha15)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha16)));
    $sql = "SELECT * FROM Baja INNER JOIN Usuario ON Baja.RFC = Usuario.RFC WHERE fecha >= '" . $date1 . "' AND fecha <= '" . $date2 . "'" . $puesto . $departamento.' ORDER BY Baja.RFC';
    $consulta = mysqli_query($conexion, $sql);
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(6);
    $sheet = $spreadsheet->getActiveSheet()->setTitle("Bajas");
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:L1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'BAJAS DEL ' . $fecha15 . ' AL ' . $fecha16);
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
            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['nombres']));
            $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['apellidop']));
            $sheet->setCellValue('D' . $i, mb_strtoupper($resultado['apellidom']));
            $sheet->setCellValue('E' . $i, $resultado['CURP']);
            $sheet->setCellValue('F' . $i, $resultado['RFC']);
            $sheet->setCellValue('G' . $i, $resultado['fechaRelLab']);
            $sheet->setCellValue('H' . $i, $resultado['puesto']);
            $sheet->setCellValue('I' . $i, $resultado['departamento']);
            $sheet->setCellValue('J' . $i, date("d/m/Y", strtotime($resultado['fecha'])));
            $sheet->setCellValue('K' . $i, date("d/m/Y H:i", strtotime($resultado[4])));
            $sheet->setCellValue('L' . $i, $resultado['razon']);
            $i++;
        }
        $spreadsheet->getActiveSheet()->getStyle('A3:L' . $i)->applyFromArray($contenido);
        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    } else {
        $fecha15 = 0;
    }

    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(7);
    $sheet = $spreadsheet->getActiveSheet()->setTitle("Beneficiarios");
    $spreadsheet->getActiveSheet()->mergeCells('B1:D1');
    $spreadsheet->getActiveSheet()->getStyle("B1")->applyFromArray($titulos);
    $sheet->setCellValue('B1', 'LISTA DE BENEFICIARIOS');
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:D2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:D2')->getFont()->getColor()->setRGB('FFFFFF');
    $sheet->setCellValue('A2', 'NOMBRE DEL USUARIO');
    $sheet->setCellValue('B2', 'NOMBRE DEL BENEFICIARIO');
    $sheet->setCellValue('C2', 'PARENTESCO');
    $sheet->setCellValue('D2', 'FECHA DE REGISTRO');
    $sql = "SELECT * FROM Beneficiario INNER JOIN Usuario ON Beneficiario.RFC = Usuario.RFC";
    $consulta = mysqli_query($conexion, $sql);
    if ($consulta && mysqli_num_rows($consulta) > 0 && $beneficiarios == 1) {
        $bandera = true;
        $i = 3;
        while ($resultado = mysqli_fetch_array($consulta)) {
            $sheet->setCellValue('A' . $i, mb_strtoupper($resultado['nombre']));
            $sheet->setCellValue('B' . $i, mb_strtoupper($resultado['beneficiario']));
            $sheet->setCellValue('C' . $i, mb_strtoupper($resultado['parentesco']));
            $sheet->setCellValue('D' . $i, date("d/m/Y H:i", strtotime($resultado[4])));
            $i++;
        }
        $spreadsheet->getActiveSheet()->getStyle('A3:D' . $i)->applyFromArray($contenido);
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    } else {
        $beneficiarios = 0;
    }

    $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha17)));
    $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha18)));
    $sql = "SELECT * FROM Pase INNER JOIN Usuario ON Pase.RFC = Usuario.RFC WHERE fecha >= '" . $date1 . "' AND fecha <= '" . $date2 . "'" . $puesto . $departamento.' ORDER BY Pase.RFC';
    $consulta = mysqli_query($conexion, $sql);
    $spreadsheet->createSheet();
    $spreadsheet->setActiveSheetIndex(8);
    $sheet = $spreadsheet->getActiveSheet()->setTitle("Pases");
    $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
    $spreadsheet->getActiveSheet()->mergeCells('C1:N1');
    $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($titulos);
    $sheet->setCellValue('C1', 'PASES DEL ' . $fecha17 . ' AL ' . $fecha18);
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath('../img/logo.png');
    $drawing->setHeight(50);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(30);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
    $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFont()->getColor()->setRGB('FFFFFF');
    $sheet->setCellValue('A2', 'ID');
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
            if($resultado[4] == 1)
                $categoria = 'SALIDA';
                

            $spreadsheet->getActiveSheet()->getCell('A' . $i)->setValueExplicit(str_pad($resultado['id_usuario'], 5, '0', STR_PAD_LEFT), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
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
            $sheet->setCellValue('M' . $i, date("d/m/Y H:i", strtotime($resultado[7])));
            $sheet->setCellValue('N' . $i, $resultado['observacion']);
            $i++;
        }
        $spreadsheet->getActiveSheet()->getStyle('A3:N' . $i)->applyFromArray($contenido);
        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    } else {
        $fecha17 = 0;
    }

    if ($fecha1 == 0) {
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Movimientos')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
    }
    if ($fecha3 == 0) {
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Descuentos')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
    }
    if ($fecha5 == 0) {
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Vacaciones')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
    }
    if ($fecha7 == 0) {
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Permisos con goce')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
    }
    if ($fecha9 == 0) {
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Permisos sin goce')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
    }
    if ($fecha13 == 0) {
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Altas')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
    }
    if ($fecha15 == 0) {
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Bajas')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
    }
    if ($fecha17 == 0) {
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Pases')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
    }
    if ($beneficiarios == 0) {
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Beneficiarios')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
    }

    if ($bandera) {
        $nombre = "reporte_".time().".xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save('../archivos/'.$nombre);
        echo "assets/archivos/".$nombre;
    } else {
        echo 0;
    }

}

$conexion->close();