<?php
include "conexion.php";
$conexion = conexion();

$id_usuario = trim($_POST["numero"]);
// $archivo = trim($_POST["archivo"]);
$RFC = trim($_POST["rfc"]);
$CURP = trim($_POST["curp"]);
$fechaRelLab = trim($_POST["ingreso"]);
$puesto = trim($_POST["puesto"]);
$departamento = trim($_POST["departamento"]);
$banca = trim($_POST["banca"]);
$afiliacion = trim($_POST["afiliacion"]);
$nombres = trim(ucwords(strtolower($_POST["nombres"])));
$apellidom = trim(ucfirst(strtolower($_POST["apellidom"])));
$apellidop = trim(ucfirst(strtolower($_POST["apellidop"])));
$trabajador = trim($_POST["trabajador"]);
$nombreEmpleado =  $apellidop . " " . $apellidom." ".$nombres;
$password = str_pad($id_usuario, 5, '0', STR_PAD_LEFT);

$sql = "SELECT RFC FROM Usuario WHERE RFC = '" . $RFC."'";
$consulta = mysqli_query($conexion, $sql);
if (mysqli_num_rows($consulta) == 0) {
    $sql = "INSERT INTO Usuario(id_usuario, categoria, contrasenia, nombre, rfc, curp, fechaRelLab, puesto,
    departamento, banca, afiliacion, apellidop, apellidom, nombres, tipoTrabajador) VALUES(" . $id_usuario .
    ", 'user', '" . $password . "', '" . $nombreEmpleado . "', '" . $RFC . "', '" . $CURP . "', '" .
        $fechaRelLab . "', '" . $puesto . "', '" . $departamento . "', NULLIF('" . $banca . "', ''), NULLIF('" .
        $afiliacion . "',''), '" . $apellidop . "' , '" . $apellidom . "', '" . $nombres . "', '" . $trabajador . "')";

    if (mysqli_query($conexion, $sql)) {
        // if ($archivo !== "") {
        //     $file = explode("/", $archivo);
        //     $de = "./../archivos/" . $file[2];
        //     $a = "./../usuario/" . $file[2];
        //     copy($de, $a);

        //     $files = glob('./../archivos/*');
        //     foreach ($files as $file) {
        //         if (is_file($file)) {
        //             unlink($file);
        //         }
        //     }
        // }
        
        echo 0;
    } else {
        echo 1;
    }
} else {
    echo 2;
}

mysqli_close($conexion);