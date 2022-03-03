<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];

include 'conexion.php';
require_once "../../vendor/autoload.php";

use Spatie\PdfToText\Pdf;

$registrar_usuario = $_POST['registrar_usuario'];
setlocale(LC_ALL, "spanish");
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
    switch ($datos[1]) {
        case 'Ene':
            return $datos[0] . '/01/' . $datos[2];
            break;
        case 'Feb':
            return $datos[0] . '/02/' . $datos[2];
            break;
        case 'Mar':
            return $datos[0] . '/03/' . $datos[2];
            break;
        case 'Abr':
            return $datos[0] . '/04/' . $datos[2];
            break;
        case 'May':
            return $datos[0] . '/05/' . $datos[2];
            break;
        case 'Jun':
            return $datos[0] . '/06/' . $datos[2];
            break;
        case 'Jul':
            return $datos[0] . '/07/' . $datos[2];
            break;
        case 'Ago':
            return $datos[0] . '/08/' . $datos[2];
            break;
        case 'Sep':
            return $datos[0] . '/09/' . $datos[2];
            break;
        case 'Oct':
            return $datos[0] . '/10/' . $datos[2];
            break;
        case 'Nov':
            return $datos[0] . '/11/' . $datos[2];
            break;
        case 'Dic':
            return $datos[0] . '/12/' . $datos[2];
            break;
        default:
            return "";
            break;
    }
}

$archivo = $_FILES['file']['tmp_name'];
file_put_contents("./prueba.txt", basename($_FILES['file']['name']));

$pdf = Pdf::getText($archivo, 'pdftotext');
$pdf = str_replace("\n", "<br>", $pdf);
$pdf = str_replace("<br>\r<br>", "<br>", $pdf);

$posid1 = strpos($pdf, ' - ');
if ($posid1 !== false) {
    $posid2 = strpos($pdf, ' - ');
    $posid1 = strrpos($pdf, "<br>", -(strlen($pdf) - $posid2)) + 4;
    $id = substr($pdf, $posid1, $posid2 - $posid1);
} else {
    $id = 0;
}

$posnombre1 = strpos($pdf, ' - ');
if ($posnombre1 !== false) {
    $posnombre1 = $posnombre1 + 3;
    $posnombre2 = strpos($pdf, '<br>', $posnombre1);
    $nombre = utf8_encode(trim(substr($pdf, $posnombre1, $posnombre2 - $posnombre1)));
} else {
    $nombre = "";
}

$posrfc1 = strpos($pdf, 'RFC:');
if ($posrfc1 !== false) {
    $posrfc1 = strpos($pdf, 'RFC:', $posrfc1 + 10) + 9;
    $posrfc2 = strpos($pdf, '<br>', $posrfc1);
    $rfc = trim(substr($pdf, $posrfc1, $posrfc2 - $posrfc1));
} else {
    $rfc = "";
}

$poscurp1 = strpos($pdf, 'CURP:');
if ($poscurp1 !== false) {
    $poscurp1 = strpos($pdf, "<br>", $poscurp1) + 4;
    $poscurp2 = strpos($pdf, '<br>', $poscurp1);
    $curp = trim(substr($pdf, $poscurp1, $poscurp2 - $poscurp1));
} else {
    $curp = "";
}
$pospuesto1 = strpos($pdf, 'Puesto:');
if ($pospuesto1 !== false) {
    $pospuesto1 = strpos($pdf, "<br>", $pospuesto1) + 4;
    $pospuesto2 = strpos($pdf, '<br>', $pospuesto1);
    $puesto = utf8_encode((trim(substr($pdf, $pospuesto1, $pospuesto2 - $pospuesto1))));
} else {
    $puesto = "";
}

$posdepa1 = strpos($pdf, 'Depto:');
if ($posdepa1 !== false) {
    $posdepa1 = strpos($pdf, "<br>", $posdepa1) + 4;
    $posdepa2 = strpos($pdf, '<br>', $posdepa1);
    $departamento = utf8_encode(trim(substr($pdf, $posdepa1, $posdepa2 - $posdepa1)));
} else {
    $departamento = "";
}

$posdias1 = strpos($pdf, 'as de Pago:');
if ($posdias1 !== false) {
    $posdias1 = $posdias1 + 11;
    $posdias2 = strpos($pdf, '<br>', $posdias1);
    $dias = trim(substr($pdf, $posdias1, $posdias2 - $posdias1));
} else {
    $dias = "";
}

if (strpos($pdf, 'Catorcenal') !== false) {
    $periodo = 1;
} else if (strpos($pdf, 'Mensual') !== false) {
    $periodo = 2;
} else if (strpos($pdf, 'Periodicidad') !== false) {
    $periodo = 3;
    $dias = 0;
} else {
    $periodo = "";
}

$posdel1 = strpos($pdf, 'Periodo');
if ($posdel1 !== false) {
    $posdel2 = strpos($pdf, " - ", $posdel1);
    $posdel1 = $posdel2 - 11;
    $del = trim(substr($pdf, $posdel1, $posdel2 - $posdel1));
    $del = fecha($del);

    $posal1 = $posdel2 + 3;
    $posal2 = strpos($pdf, '<br>', $posal1);
    $al = trim(substr($pdf, $posal1, $posal2 - $posal1));
    $al = fecha($al);
} else {
    $del = "";
    $al = "";
}

$pospago1 = strpos($pdf, 'Fecha Pago:');
if ($pospago1 !== false) {
    $pospago1 = $pospago1 + 12;
    $pospago2 = strpos($pdf, '<br>', $pospago1);
    $pago = trim(substr($pdf, $pospago1, $pospago2 - $pospago1));
    $pago = fecha($pago);
} else {
    $pago = "";
}

$posinicio1 = strpos($pdf, 'Lab:');
if ($posinicio1 !== false) {
    $posinicio1 = $posinicio1 + 5;
    $posinicio2 = strpos($pdf, '<br>', $posinicio1);
    $inicio = trim(substr($pdf, $posinicio1, $posinicio2 - $posinicio1));
    $inicio = fecha($inicio);
} else {
    $inicio = "";
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
    if (validar_fecha($del) && validar_fecha($al) && validar_fecha($pago) && $periodo != "" && validar_fecha($inicio)) {
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
                                VALUES('" . $RFC . "', STR_TO_DATE('" . $inicio . "','%d/%m/%Y'),'alta', 'alta de empleado',".$id_prenomina.")";
                                $consulta = $conexion->query($sql);
                            }
                        }
                    }
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
