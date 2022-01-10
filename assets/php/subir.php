<?php
include 'conexion.php';
require_once "../../vendor/autoload.php";

use Spatie\PdfToText\Pdf;

$registrar_usuario = $_POST['registrar_usuario'];
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$conexion = conexion();

$ruta_nominas = "../nominas/";
if (!file_exists($ruta_nominas)) {
    mkdir($ruta_nominas, 0777, true);
}

function validar_fecha($date){
    $format = 'd/m/Y';
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

$archivo = $_FILES['file']['tmp_name'];

$pdf = Pdf::getText($archivo, 'pdftotext');
$pdf = str_replace("\n", "<br>", $pdf);
$pdf = str_replace("<br>\r<br>", "<br>", $pdf);

if (strpos($pdf, ' - ') !== false) {
    $posid2 = strpos($pdf, ' - ');
    $posid1 = strrpos($pdf, "<br>", - (strlen($pdf) - $posid2)) + 4;
    $id = substr($pdf, $posid1, $posid2 - $posid1);
} else {
    $id = 0;
}

if (strpos($pdf, ' - ') !== false) {
    $posnombre1 = strpos($pdf, ' - ') + 3;
    $posnombre2 = strpos($pdf, '<br>', $posnombre1);
    $nombre = utf8_encode(trim(substr($pdf, $posnombre1, $posnombre2 - $posnombre1)));
} else {
    $nombre = "";
}

if (strpos($pdf, 'RFC:') !== false) {
    $posrfc1 = strpos($pdf, 'RFC:', strpos($pdf, 'RFC:') + 1) + 9;
    $posrfc2 = strpos($pdf, '<br>', $posrfc1);
    $rfc = trim(substr($pdf, $posrfc1, $posrfc2 - $posrfc1));
} else {
    $rfc = "";
}

if (strpos($pdf, 'CURP:') !== false) {
    $poscurp1 = strpos($pdf, 'CURP:') + 10;
    $poscurp2 = strpos($pdf, '<br>', $poscurp1);
    $curp = trim(substr($pdf, $poscurp1, $poscurp2 - $poscurp1));
} else {
    $curp = "";
}

if (strpos($pdf, 'Puesto:') !== false) {
    $pospuesto1 = strpos($pdf, 'Puesto:') + 12;
    $pospuesto2 = strpos($pdf, '<br>', $pospuesto1);
    $puesto = utf8_encode((trim(substr($pdf, $pospuesto1, $pospuesto2 - $pospuesto1))));
} else {
    $puesto = "";
}

if (strpos($pdf, 'Depto:') !== false) {
    $posdepa1 = strpos($pdf, 'Depto:') + 11;
    $posdepa2 = strpos($pdf, '<br>', $posdepa1);
    $depa = utf8_encode(trim(substr($pdf, $posdepa1, $posdepa2 - $posdepa1)));
} else {
    $depa = "";
}

if (strpos($pdf, 'as de Pago:') !== false) {
    $posdias1 = strpos($pdf, 'as de Pago:') + 11;
    $posdias2 = strpos($pdf, '<br>', $posdias1);
    $dias = utf8_encode((trim(substr($pdf, $posdias1, $posdias2 - $posdias1))));
} else {
    $dias = "";
}

$pospago1 = strpos($pdf, 'Fecha Pago:');
if ($pospago1 !== false) {
    $pospago1 = $pospago1 + 12;
    $pospago2 = strpos($pdf, '<br>', $pospago1);
    $pago = trim(substr($pdf, $pospago1, $pospago2 - $pospago1));
    $datos = explode("/", $pago);
    switch ($datos[1]) {
        case 'Ene':
            $pago = $datos[0] . '/01/' . $datos[2];
            break;
        case 'Feb':
            $pago = $datos[0] . '/02/' . $datos[2];
            break;
        case 'Mar':
            $pago = $datos[0] . '/03/' . $datos[2];
            break;
        case 'Abr':
            $pago = $datos[0] . '/04/' . $datos[2];
            break;
        case 'May':
            $pago = $datos[0] . '/05/' . $datos[2];
            break;
        case 'Jun':
            $pago = $datos[0] . '/06/' . $datos[2];
            break;
        case 'Jul':
            $pago = $datos[0] . '/07/' . $datos[2];
            break;
        case 'Ago':
            $pago = $datos[0] . '/08/' . $datos[2];
            break;
        case 'Sep':
            $pago = $datos[0] . '/09/' . $datos[2];
            break;
        case 'Oct':
            $pago = $datos[0] . '/10/' . $datos[2];
            break;
        case 'Nov':
            $pago = $datos[0] . '/11/' . $datos[2];
            break;
        case 'Dic':
            $pago = $datos[0] . '/12/' . $datos[2];
            break;
        default:
            break;
    }
} else {
    $pago = "";
}

$posinicio1 = strpos($pdf, 'Lab:');
if ($posinicio1 !== false) {
    $posinicio1 = $posinicio1 + 5;
    $posinicio2 = strpos($pdf, '<br>', $posinicio1);
    $inicio = trim(substr($pdf, $posinicio1, $posinicio2 - $posinicio1));
    $datos = explode("/", $inicio);
    switch ($datos[1]) {
        case 'Ene':
            $inicio = $datos[0] . '/01/' . $datos[2];
            break;
        case 'Feb':
            $inicio = $datos[0] . '/02/' . $datos[2];
            break;
        case 'Mar':
            $inicio = $datos[0] . '/03/' . $datos[2];
            break;
        case 'Abr':
            $inicio = $datos[0] . '/04/' . $datos[2];
            break;
        case 'May':
            $inicio = $datos[0] . '/05/' . $datos[2];
            break;
        case 'Jun':
            $inicio = $datos[0] . '/06/' . $datos[2];
            break;
        case 'Jul':
            $inicio = $datos[0] . '/07/' . $datos[2];
            break;
        case 'Ago':
            $inicio = $datos[0] . '/08/' . $datos[2];
            break;
        case 'Sep':
            $inicio = $datos[0] . '/09/' . $datos[2];
            break;
        case 'Oct':
            $inicio = $datos[0] . '/10/' . $datos[2];
            break;
        case 'Nov':
            $inicio = $datos[0] . '/11/' . $datos[2];
            break;
        case 'Dic':
            $inicio = $datos[0] . '/12/' . $datos[2];
            break;
        default:
            break;
    }
} else {
    $inicio = "";
}

$arraypago = explode("/", $pago);
$nombreNomina = $id.implode("_", $arraypago).'.pdf';
$url = 'assets/nominas/' . $nombreNomina;
$nombre = str_replace('  ', ' ', $nombre);
$arraynombre = explode(" ", $nombre);
$apellidop = array_shift($arraynombre);
$apellidom = array_shift($arraynombre);
$nombres = implode(" ", $arraynombre);
$password = str_pad($id, 5, '0', STR_PAD_LEFT);

$sql = "SELECT id_archivo FROM Archivo WHERE url = '" . $url . "'";
$consulta = mysqli_query($conexion, $sql);
$total = mysqli_num_rows($consulta);

if ($total == 0) {
    if (validar_fecha($pago)) {
        $sql = "INSERT INTO Archivo(nombre, url, fecha_pago, RFC, puesto, departamento, dias_pago) VALUES(
            '" . $nombre . "',
            '" . $url . "',
            STR_TO_DATE('" . $pago . "','%d/%m/%Y'),
            '" . $rfc . "',
            '" . $puesto . "',
            '" . $depa . "',
            " . (int) $dias . "          
        )";

        if (mysqli_query($conexion, $sql)) {
            if ($registrar_usuario == 1) {
                $sql = "INSERT INTO Usuario(id_usuario, categoria, contrasenia, nombre, RFC, CURP, fechaRelLab, 
                puesto, departamento, apellidop, apellidom, nombres) VALUES(
                    " . $id . ",
                    'user',
                    '" . $password . "', 
                    '" . $nombre . "',
                    '" . $rfc . "',
                    '" . $curp . "',
                    '" . $inicio . "',
                    '" . $puesto . "',
                    '" . $depa . "', 
                    '" . $apellidop . "',
                    '" . $apellidom . "',
                    '" . $nombres . "')";
                if (mysqli_query($conexion, $sql)) {
                }
            }
            $target = $_SERVER['DOCUMENT_ROOT'] ."/".$url;
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
// echo $depa;
// echo "\n";
// echo $dias;
// echo "\n";