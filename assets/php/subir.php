<?php
session_start();
setlocale(LC_ALL, "spanish");

include 'conexion.php';
include '../../pdf/PdfToText.php';

$id_prenomina = $_SESSION["id_prenomina"];
$registrar_usuario = $_POST['registrar_usuario'];
$conexion = conexion();

$ruta_nominas = "../nominas/";
if (!file_exists($ruta_nominas)) {
    mkdir($ruta_nominas, 0777, true);
}

function validar_fecha($date)
{
    $format = 'd/m/Y';
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function fecha($fecha)
{
    $datos = explode("/", $fecha);
    if (count($datos) == 3) {
        switch ($datos[1]) {
            case 'ENE':
                return $datos[0] . '/01/' . $datos[2];
                break;
            case 'FEB':
                return $datos[0] . '/02/' . $datos[2];
                break;
            case 'MAR':
                return $datos[0] . '/03/' . $datos[2];
                break;
            case 'ABR':
                return $datos[0] . '/04/' . $datos[2];
                break;
            case 'MAY':
                return $datos[0] . '/05/' . $datos[2];
                break;
            case 'JUN':
                return $datos[0] . '/06/' . $datos[2];
                break;
            case 'JUL':
                return $datos[0] . '/07/' . $datos[2];
                break;
            case 'AGO':
                return $datos[0] . '/08/' . $datos[2];
                break;
            case 'SEP':
                return $datos[0] . '/09/' . $datos[2];
                break;
            case 'OCT':
                return $datos[0] . '/10/' . $datos[2];
                break;
            case 'NOV':
                return $datos[0] . '/11/' . $datos[2];
                break;
            case 'DIC':
                return $datos[0] . '/12/' . $datos[2];
                break;
            default:
                return "";
                break;
        }
    } else {
        return "";
    }

}

function calcular($string, $pdf)
{
    $pos1 = strpos($pdf, $string);
    if ($pos1 !== false) {
        $pos1 = $pos1 + strlen($string);
        $pos2 = strpos($pdf, "|", $pos1);
        return trim(substr($pdf, $pos1, ($pos2 - $pos1)));
    } else {
        return "";
    }
}

function calcular_reversa($string, $pdf, $offset = 0)
{
    $pos1 = strpos($pdf, $string);
    if ($pos1 !== false) {
        $pos1 = $pos1 - $offset;
        $text = substr($pdf, 0, $pos1);
        $pos2 = strrpos($text, "|") + 1;
        return trim(substr($pdf, $pos2, ($pos1 - $pos2)));
    } else {
        return "";
    }
}


$archivo = $_FILES['file']['tmp_name'];
$pdf = new PdfToText();
$pdf->BlockSeparator = "|";
$pdf->Separator = "|";
$pdf->Options = 0x00000400;
// $pdf->Options = 0x00000000;
$pdf->Load($archivo);
$pdf = mb_strtoupper($pdf->Text);

$indice = strpos($pdf, 'PERCEPCIONES');
$pdf = $indice ? substr($pdf, 0, $indice) : $pdf;

$pdf = str_replace("\n", "|", $pdf);
$pdf = str_replace("\r", "|\r", $pdf);
$pdf = str_replace(":", "", $pdf);
$pdf = preg_replace('/([|])\1+/', '|', $pdf);
$pdf = str_replace("  ", " ", $pdf);
$pdf = str_replace("Á", "A", $pdf);
$pdf = str_replace("É", "E", $pdf);
$pdf = str_replace("Í", "I", $pdf);
$pdf = str_replace("Ó", "O", $pdf);
$pdf = str_replace("Ú", "U", $pdf);


$pos1 = strpos($pdf, 'RFC|');
if ($pos1 !== false) {
    $pos1 = strpos($pdf, 'RFC|', strpos($pdf, 'RFC|') + 1) + 4;
    $pos2 = strpos($pdf, "|", $pos1);
    $rfc = trim(substr($pdf, $pos1, ($pos2 - $pos1)));
} else {
    $rfc = "";
}

if (strpos($pdf, 'CATORCENAL') !== false) {
    $periodo = 1;
} else if (strpos($pdf, 'MENSUAL') !== false) {
    $periodo = 2;
} else if (strpos($pdf, 'PERIODICIDAD') !== false) {
    $periodo = 3;
    $dias = 0;
} else if (strpos($pdf, 'QUINCENAL') !== false) {
    $periodo = 1;
}else {
    $periodo = "";
}

$id = calcular_reversa(" - ", $pdf);
$nombre = calcular(" - ", $pdf);
$curp = calcular("CURP|", $pdf);
$puesto = calcular("PUESTO|", $pdf);
$departamento = calcular("DEPTO|", $pdf);
$dias = calcular("DIAS DE PAGO|", $pdf);
$pago = fecha(calcular("FECHA PAGO|", $pdf));
$inicio = fecha(calcular("LAB|", $pdf));
// $del = fecha(calcular_reversa("|-|", $pdf));
// $al = fecha(calcular("|-|", $pdf));

$patron = "/\d{2}\/[A-Z]{3}\/\d{4}(?:-|\|-\|)\d{2}\/[A-Z]{3}\/\d{4}/";
$fechas = [];
preg_match_all($patron, $pdf, $fechas);
$del = "";
$al = "";

if (count($fechas[0]) >= 1) {
    $partes = preg_split("/-|\|-\|/", $fechas[0][0]);
    if (count($partes) == 2) {
        $del = fecha($partes[0]);
        $al = fecha($partes[1]);
    }
}

$arraypago = explode("/", $pago);
$nombreNomina = $id . implode("_", $arraypago) . '.pdf';
$nombre = str_replace('  ', ' ', $nombre);
$arraynombre = explode(" ", $nombre);
$apellidop = array_shift($arraynombre);
$apellidom = array_shift($arraynombre);
$nombres = implode(" ", $arraynombre);
$password = str_pad($id, 5, '0', STR_PAD_LEFT);

$sql = "SELECT id_archivo FROM Archivo WHERE url = '" . $nombreNomina . "'";
$consulta = $conexion->query($sql);
$total = mysqli_num_rows($consulta);

if ($total == 0) {
    if (validar_fecha($del) && validar_fecha($al) && validar_fecha($pago) && $periodo != "") {
        $sql = "INSERT INTO Archivo(del, al,fecha_pago,nombre, url, RFC, puesto, departamento, dias_pago, id_periodo) VALUES(
            STR_TO_DATE('" . $del . "','%d/%m/%Y'),
            STR_TO_DATE('" . $al . "','%d/%m/%Y'),
            STR_TO_DATE('" . $pago . "','%d/%m/%Y'),
            '" . $nombre . "',
            '" . $nombreNomina . "',
            '" . $rfc . "',
            '" . $puesto . "',
            '" . $departamento . "',
            " . (int) $dias . ",
            " . $periodo . "
        )";

        if ($conexion->query($sql)) {
            if ($registrar_usuario == 1) {
                if (validar_fecha($inicio)) {
                    if ($periodo == 3) {
                        $periodo = 1;
                    }

                    $sql = "SELECT * FROM Departamento WHERE nombre = '" . $departamento . "'";
                    $consulta = $conexion->query($sql);
                    $total = mysqli_num_rows($consulta);
                    if ($consulta && $total == 0) {
                        $sql = "INSERT INTO Departamento(nombre) VALUES(NULLIF('" . $departamento . "', ''))";
                        if ($conexion->query($sql)) {
                            $id_depa = mysqli_insert_id($conexion);
                        }
                    } elseif ($consulta && $total > 0) {
                        $res = mysqli_fetch_row($consulta);
                        $id_depa = $res[0];
                    }

                    $sql = "SELECT * FROM Puesto WHERE nombre = '" . $puesto . "' AND id_departamento = " . $id_depa;
                    $consulta = $conexion->query($sql);
                    $total = mysqli_num_rows($consulta);

                    if ($consulta && $total == 0) {
                        $sql = "INSERT INTO Puesto(nombre, id_departamento) VALUES(NULLIF('" . $puesto . "', ''), " . $id_depa . ")";
                        if ($conexion->query($sql)) {
                            $id_puesto = mysqli_insert_id($conexion);

                            $sql = "INSERT INTO Usuario(categoria, contrasenia, nombre, RFC) VALUES(
                                'user',
                                '" . $password . "',
                                '" . $nombre . "',
                                '" . $rfc . "'
                            )";

                            if ($conexion->query($sql)) {

                                $sql = "INSERT INTO Empleado(id_empleado, RFC, CURP, fechaRelLab,
                                id_puesto, apellidop, apellidom, nombres, id_periodo) VALUES(
                                    " . $id . ",
                                    '" . $rfc . "',
                                    '" . $curp . "',
                                    '" . $inicio . "',
                                    " . $id_puesto . ",
                                    '" . $apellidop . "',
                                    '" . $apellidom . "',
                                    '" . $nombres . "',
                                    " . $periodo . ")";
                                if ($conexion->query($sql)) {
                                    $sql = "INSERT INTO Historial(RFC,fecha,tipo,descripcion,id_prenomina)
                                    VALUES('" . $RFC . "', STR_TO_DATE('" . $inicio . "','%d/%m/%Y'),'alta', 'alta de empleado'," . $id_prenomina . ")";
                                    $consulta = $conexion->query($sql);
                                }
                            }
                        }
                    }
                } else {
                    echo 0;
                }
            }

            $target = "../nominas/" . $nombreNomina;
            move_uploaded_file($archivo, $target);
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
} else {
    echo 2;
}

$conexion->close();

// file_put_contents("./prueba.txt", $pdf);
// echo $id;
// echo "\n";
// echo $periodo;
// echo "\n";
// echo $nombre;
// echo "\n";
// echo $curp;
// echo "\n";
// echo $rfc;
// echo "\n";
// echo $inicio;
// echo "\n";
// echo $pago;
// echo "\n";
// echo $puesto;
// echo "\n";
// echo $departamento;
// echo "\n";
// echo $dias;
// echo "\n";
// echo $del;
// echo "\n";
// echo $al;
// echo "\n";