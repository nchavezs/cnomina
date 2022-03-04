<?php
include "conexion.php";
$conexion = conexion();
$del = $_POST['del'];
$al = $_POST['al'];

$fecha1 = date("Y-m-d", strtotime(str_replace('/', '-', $del)));
$fecha2 = date("Y-m-d", strtotime(str_replace('/', '-', $al)));

$sql = "SELECT id_archivo FROM Archivo";
$consulta = $conexion->query($sql);
$total1 = mysqli_num_rows($consulta);

$sql = "SELECT url FROM Archivo WHERE STR_TO_DATE(fecha_pago, '%d/%m/%Y') BETWEEN '".$fecha1. "' AND '". $fecha2."'";
$consulta = $conexion->query($sql);
if ($consulta) {
    while($res = mysqli_fetch_row($consulta)){
        $url = explode("/", $res[0]); 
        $url = "../".$url[1]."/".$url[2];
        if (is_file($url)) {
            unlink($url);
        }
    }

    $sql = "DELETE FROM Archivo WHERE STR_TO_DATE(fecha_pago, '%d/%m/%Y') BETWEEN '".$fecha1. "' AND '". $fecha2."'";
    if ($conexion->query($sql)) {
        $sql = "SELECT id_archivo FROM Archivo";
        $consulta = $conexion->query($sql);
        $total2 = mysqli_num_rows($consulta);
        $total = $total1 - $total2;
        echo $total;
    }else{
        echo 0;
    }
} else {
    echo 0;
}

$conexion->close();