<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
require_once "../../vendor/autoload.php";
$conexion = conexion();

function eliminar_simbolos($string)
{

    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ç', 'Ç'),
        array('c', 'C'),
        $string
    );

    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "<code>", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            ".", " "),
        ' ',
        $string
    );
    return $string;
}

function validar_fecha($date)
{
    $format = 'd/m/Y';
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

$archivo = $_FILES['file']['tmp_name'];
$total = 0;
$errores = [];

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($archivo);
$sheet = $spreadsheet->getActiveSheet();
$highestRow = $sheet->getHighestRow();
$highestColumn = "L";
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

$formato = false;
$datos = [];
for ($col = 1; $col <= $highestColumnIndex; ++$col) {
    $valor = $sheet->getCellByColumnAndRow($col, 1)->getValue();
    array_push($datos, $valor);
}
if ($datos[11] == "PERIODO") {
    $formato = true;
}

if ($formato) {
    for ($row = 2; $row <= $highestRow; ++$row) {
        $datos = [];
        $types = [];
        $errors = [];

        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
            $type = $sheet->getCellByColumnAndRow($col, $row)->getDataType();
            $valor = $sheet->getCellByColumnAndRow($col, $row)->getValue();
            array_push($datos, $valor);
            array_push($types, $type);
        }

        $id_empleado = $datos[0];
        $nombres = trim(ucwords(mb_strtolower($datos[1])));
        $apellidop = trim(ucfirst(mb_strtolower($datos[2])));
        $apellidom = trim(ucfirst(mb_strtolower($datos[3])));
        $departamento = eliminar_simbolos(mb_strtoupper($datos[4]));
        $puesto = eliminar_simbolos(mb_strtoupper($datos[5]));
        $fechaRelLab = trim($datos[6]);
        $CURP = eliminar_simbolos(mb_strtoupper($datos[7]));
        $RFC = eliminar_simbolos(mb_strtoupper($datos[8]));
        $banca = eliminar_simbolos($datos[9]);
        $afiliacion = eliminar_simbolos($datos[10]);
        $periodo = eliminar_simbolos(mb_strtoupper($datos[11]));

        $password = str_pad($datos[0], 5, '0', STR_PAD_LEFT);
        $nombreEmpleado = $apellidop . " " . $apellidom . " " . $nombres;
        if ($types[6] == "n") {
            $fechaRelLab = gmdate("d/m/Y", \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($datos[5]));
        }

        mysqli_autocommit($conexion, true);

        $sql = "SELECT * FROM Usuario WHERE RFC = '" . $RFC . "'";
        $consulta = $conexion->query($sql);

        if ($consulta && mysqli_num_rows($consulta) == 0) {
            if (validar_fecha($fechaRelLab)) {
                $sql = "SELECT * FROM Departamento WHERE nombre = '" . $departamento . "'";
                $consulta = $conexion->query($sql);

                if ($consulta && mysqli_num_rows($consulta) > 0) {
                    $res = mysqli_fetch_array($consulta);
                    $departamento = $res["id_departamento"];

                    $sql = "SELECT * FROM Puesto WHERE nombre = '" . $puesto . "' AND id_departamento = " . $departamento;
                    $consulta = $conexion->query($sql);

                    if ($consulta && mysqli_num_rows($consulta) > 0) {
                        $res = mysqli_fetch_array($consulta);
                        $puesto = $res["id_puesto"];

                        $sql = "SELECT * FROM Plaza WHERE id_puesto = " . $puesto . " AND RFC IS NULL LIMIT 1";
                        $consulta = $conexion->query($sql);

                        if ($consulta && mysqli_num_rows($consulta) > 0) {
                            $res = mysqli_fetch_array($consulta);
                            $plaza = $res["id_plaza"];

                            $sql = "SELECT * FROM Periodo WHERE nombre = '" . $periodo . "'";
                            $consulta = $conexion->query($sql);
                            if ($consulta && mysqli_num_rows($consulta) > 0) {
                                $res = mysqli_fetch_array($consulta);
                                $periodo = $res["id_periodo"];
                            }
                            // -------------------------------------------------------------------------------------
                            mysqli_autocommit($conexion, false);

                            $sql1 = "INSERT INTO Usuario(categoria,contrasenia, nombre, RFC) VALUES(
                                'user',
                                '" . $password . "',
                                '" . $nombreEmpleado . "',
                                '" . $RFC . "')";

                            $sql2 = "INSERT INTO Empleado(
                                id_empleado,
                                RFC,
                                CURP,
                                fechaRelLab,
                                id_puesto,
                                banca,
                                afiliacion,
                                apellidop,
                                apellidom,
                                nombres,
                                id_periodo) VALUES(
                                " . $id_empleado . ",
                                '" . $RFC . "',
                                '" . $CURP . "',
                                '" . $fechaRelLab . "',
                                " . $puesto . ",
                                NULLIF('" . $banca . "', ''),
                                NULLIF('" . $afiliacion . "',''),
                                '" . $apellidop . "' ,
                                '" . $apellidom . "',
                                '" . $nombres . "',
                                " . $periodo . ")";
                            // -------------------------------------------------------------------------------------
                            $ano_actual = date("Y");
                            $ano_fecha = date("Y", strtotime(str_replace("/", "-", $fechaRelLab)));

                            if ($ano_actual == $ano_fecha) {
                                $fecha_inicio = $fechaRelLab;
                            } else {
                                $fecha_inicio = "01/01/" . $ano_actual;
                            }
                            // -------------------------------------------------------------------------------------

                            $sql3 = "UPDATE Plaza SET RFC = '" . $RFC . "' WHERE id_plaza = " . $plaza;

                            $sql4 = "INSERT INTO Historial_Plaza(
                                id_plaza,
                                fecha_inicio,
                                RFC) VALUES(
                                " . $plaza . ",
                                STR_TO_DATE('" . $fecha_inicio . "','%d/%m/%Y'),
                                '" . $RFC . "')";

                                $sql5 = "INSERT INTO Historial(RFC,fecha,tipo,descripcion,id_prenomina) 
                                VALUES('" . $RFC . "', STR_TO_DATE('" . $fechaRelLab . "','%d/%m/%Y'),'alta', 'alta de empleado',".$id_prenomina.")";

                            if (!$conexion->query($sql1)) {
                                $errors[] = $conexion->error;
                                foreach ($errors as $error) {
                                    array_push($errores, $error);
                                }
                            }
                            if (!$conexion->query($sql2)) {
                                $errors[] = $conexion->error;
                                foreach ($errors as $error) {
                                    array_push($errores, $error);
                                }
                            }
                            if (!$conexion->query($sql3)) {
                                $errors[] = $conexion->error;
                                foreach ($errors as $error) {
                                    array_push($errores, $error);
                                }
                            }
                            if (!$conexion->query($sql4)) {
                                $errors[] = $conexion->error;
                                foreach ($errors as $error) {
                                    array_push($errores, $error);
                                }
                            }

                            if (!$conexion->query($sql5)) {
                                $errors[] = $conexion->error;
                                foreach ($errors as $error) {
                                    array_push($errores, $error);
                                }
                            }

                            if (count($errors) === 0) {
                                $conexion->commit();
                                $total++;
                            } else {
                                $conexion->rollback();
                            }

                        } else {
                            array_push($errores, 'FILA ' . $row . ': plazas no disponibles');
                        }
                    } else {
                        array_push($errores, 'FILA ' . $row . ': puesto no existe en este departamento');
                    }
                } else {
                    array_push($errores, 'FILA ' . $row . ': departamento no existe');
                }
            } else {
                array_push($errores, 'FILA ' . $row . ': formato de fecha inválida');
            }
        } else {
            array_push($errores, 'FILA ' . $row . ': ya existe un empleado con este RFC');
        }
    }

    $conexion->close();
}

$html = "<div class='formulario_caja'>
            <div class='formulario text-center'>
                <h2 class='negrita text-primary'>Información de registro</h2>
                <p class='text-primary pt-3'> " . $total . " de un total de " . --$highestRow . "</p>";
if (count($errores) > 0) {
    $html = $html . "<div class='log'>
    <h5 class='text-muted'>La siguiente lista muesta los errores encontrados. </h5>";

    foreach ($errores as $error) {
        $html = $html . "<h6 class='text-muted'>" . $error . "<h6>";
    }
    $html = $html . "</div>";
}
$html = $html . "</div></div></div>";

$datos["formato"] = $formato;
$datos["html"] = $html;

echo json_encode($datos);
