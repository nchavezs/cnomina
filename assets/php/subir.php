<?php
$check1 = $_POST['check1'];

date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
include 'conexion.php';
$conexion = conexion();
include '../../pdf/PdfToText.phpclass';

$ruta = '../archivos/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$ruta_nominas = "../nominas/";
if (!file_exists($ruta_nominas)) {
    mkdir($ruta_nominas, 0777, true);
}

$archivo = $ruta . basename($_FILES['file']['name']);
move_uploaded_file($_FILES['file']['tmp_name'], $archivo);

$pdf = new PdfToText();
$pdf->BlockSeparator = "|";
$pdf->Separator = "|";
$pdf->Load($archivo);
$pdf = $pdf->Text;
$pdf = str_replace("\n", "|", $pdf);
$pdf = str_replace("\r", "|", $pdf);
$pdf = str_replace(":", "", $pdf);
$pdf = str_replace("  ", " ", $pdf);
$pdf = str_replace("Á", "A", $pdf);
$pdf = str_replace("É", "E", $pdf);
$pdf = str_replace("Í", "I", $pdf);
$pdf = str_replace("Ó", "O", $pdf);
$pdf = str_replace("Ú", "U", $pdf);

// for ($i = 0; $i < 10; $i++) {
//     $pdf = str_replace("||", "|", $pdf);
// }

$pdf = preg_replace('/([|])\1+/', '|', $pdf);

if (strpos($pdf, ' - ') !== false) {
    $posid2 = strpos($pdf, ' - ');
    $posid1 = strrpos($pdf, "|", -(strlen($pdf) - $posid2)) + 1;
    $id = substr($pdf, $posid1, $posid2 - $posid1);
} else {
    $id = 0;
}

if (strpos($pdf, ' - ') !== false) {
    $posnombre1 = strpos($pdf, ' - ') + 3;
    $posnombre2 = strpos($pdf, '|', $posnombre1);
    $nombre = trim(substr($pdf, $posnombre1, $posnombre2 - $posnombre1));
    $nombre = str_replace("  ", " ", $nombre);
} else {
    $nombre = "";
}

if (strpos($pdf, 'RFC') !== false) {
    $posrfc1 = strpos($pdf, 'RFC', strpos($pdf, 'RFC') + 1) + 4;
    $posrfc2 = strpos($pdf, '|', $posrfc1);
    $rfc = trim(substr($pdf, $posrfc1, $posrfc2 - $posrfc1));
    $rfc = str_replace(" ", "", $rfc);
} else {
    $rfc = "";
}

if (strpos($pdf, 'CURP') !== false) {
    $poscurp1 = strpos($pdf, 'CURP') + 5;
    $poscurp2 = strpos($pdf, '|', $poscurp1);
    $curp = trim(substr($pdf, $poscurp1, $poscurp2 - $poscurp1));
    $curp = str_replace(" ", "", $curp);
} else {
    $curp = "";
}

if (strpos($pdf, 'Puesto') !== false) {
    $pospuesto1 = strpos($pdf, 'Puesto') + 7;
    $pospuesto2 = strpos($pdf, '|', $pospuesto1);
    $puesto = (trim(substr($pdf, $pospuesto1, $pospuesto2 - $pospuesto1)));
} else {
    $puesto = "";
}

if (strpos($pdf, 'Depto') !== false) {
    $posdepa1 = strpos($pdf, 'Depto') + 6;
    $posdepa2 = strpos($pdf, '|', $posdepa1);
    $depa = trim(substr($pdf, $posdepa1, $posdepa2 - $posdepa1));
} else {
    $depa = "";
}

$pospago1 = strpos($pdf, 'Fecha Pago');
if ($pospago1 !== false) {
    $pospago1 = $pospago1 + 11;
    $pospago2 = strpos($pdf, '|', $pospago1);
    $pago = trim(substr($pdf, $pospago1, $pospago2 - $pospago1));
    $datos = explode("/", $pago);
    if (sizeof($datos)==3) {
        $quincena = strpos($pdf, 'Quincena');
        $dia = $datos[0];

        if($quincena === false) {
            if($dia <= 15)
                $dia = 0;
            else if($dia > 15)
                $dia = 33;
        }
        
        $ano = $datos[2];
        switch ($datos[1]) {
            case 'Ene':
                $pago = $datos[0] . '/01/' . $datos[2];
                $mes = '01';
                break;
            case 'Feb':
                $pago = $datos[0] . '/02/' . $datos[2];
                $mes = '02';
                break;
            case 'Mar':
                $pago = $datos[0] . '/03/' . $datos[2];
                $mes = '03';
                break;
            case 'Abr':
                $pago = $datos[0] . '/04/' . $datos[2];
                $mes = '04';
                break;
            case 'May':
                $pago = $datos[0] . '/05/' . $datos[2];
                $mes = '05';
                break;
            case 'Jun':
                $pago = $datos[0] . '/06/' . $datos[2];
                $mes = '06';
                break;
            case 'Jul':
                $pago = $datos[0] . '/07/' . $datos[2];
                $mes = '07';
                break;
            case 'Ago':
                $pago = $datos[0] . '/08/' . $datos[2];
                $mes = '08';
                break;
            case 'Sep':
                $pago = $datos[0] . '/09/' . $datos[2];
                $mes = '09';
                break;
            case 'Oct':
                $pago = $datos[0] . '/10/' . $datos[2];
                $mes = '10';
                break;
            case 'Nov':
                $pago = $datos[0] . '/11/' . $datos[2];
                $mes = '11';
                break;
            case 'Dic':
                $pago = $datos[0] . '/12/' . $datos[2];
                $mes = '12';
                break;
            default:
                break;
        }
    } else {
        $dia = 0;
        $mes = 0;
        $ano = 0;
    }
} else {
    $pago = "";
    $dia = 0;
    $mes = 0;
    $ano = 0;
}

$posinicio1 = strpos($pdf, 'Fecha Ini');
if ($posinicio1 !== false) {
    $posinicio2 = strpos($pdf, 'Fecha Ini') - 1;
    $posinicio1 = strrpos($pdf, "|", -(strlen($pdf) - $posinicio2) - 1) + 1;
    $inicio = trim(substr($pdf, $posinicio1, $posinicio2 - $posinicio1));
    $datos = explode("/", $inicio);
    if (sizeof($datos) == 3) {
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
    }else{
        $ano = 0;
    }

} else {
    $inicio = "";
}

$nombreNomina = $rfc . '_' . $dia . '-' . $mes . '-' . $ano . '.pdf';
$fechaSubida = date("d/m/Y");
$url = 'assets/nominas/' . $nombreNomina;
$arraynombre = explode(" ", $nombre);
$apellidop = array_shift($arraynombre);
$apellidom = array_shift($arraynombre);
$nombres = implode(" ", $arraynombre);
$password = str_pad($id, 5, '0', STR_PAD_LEFT);

$sql = "SELECT * FROM Archivo WHERE nombre = '" . $nombreNomina . "'";
$consulta = mysqli_query($conexion, $sql);
$total = mysqli_num_rows($consulta);


if ($total == 0) {
    if ($ano != 0) {
        $sql = "INSERT INTO Archivo(id_usuario, nombre, fecha_subida, url, dia, mes, ano, fecha_pago, nombreEmpleado, RFC, CURP,
            fechaRelLab, puesto, departamento) VALUES(" . $id . ", '" . $nombreNomina . "', '" . $fechaSubida . "', '" .
            $url . "', '" . $dia . "', '" . $mes . "', '" . $ano . "', '" . $pago . "', '" . $nombre . "', '" .
            $rfc . "', '" . $curp . "', '" . $inicio . "', '" . $puesto . "', '" . $depa . "')";

        if (mysqli_query($conexion, $sql)) {
            $sql = "INSERT INTO Usuario(id_usuario, categoria, contrasenia, nombre, RFC, CURP, fechaRelLab, puesto,
                departamento, apellidop, apellidom, nombres) VALUES(" . $id . ", 'user', '" . $password . "', '" .
                $nombre . "', '" . $rfc . "', '" . $curp . "', '" . $inicio . "', '" . $puesto . "', '" . $depa . "', '" .
                $apellidop . "','" . $apellidom . "','" . $nombres . "')";
            if ($check1 == 1) {
                mysqli_query($conexion, $sql);
            }
            copy('./../archivos/' . basename($_FILES['file']['name']), './../nominas/' . $nombreNomina);
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

if (is_file($archivo)) {
    unlink($archivo);
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