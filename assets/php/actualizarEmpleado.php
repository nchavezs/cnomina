<?php
session_start();
include "conexion.php";
include "rol.php";
$rol = rol();
if ($rol != 1) {
    echo 2;
} else {

    $conexion = conexion();
    $id_usuario = trim($_POST["id"]);
    // $archivo = trim($_POST["archivo"]);
    $ingreso = trim($_POST["ingreso"]);
    $RFC = trim($_POST["rfc"]);
    $CURP = trim($_POST["curp"]);
    // $puesto = trim($_POST["puesto"]);
    // $departamento = trim($_POST["departamento"]);
    $banca = trim($_POST["banca"]);
    $afiliacion = trim($_POST["afiliacion"]);
    $nombres = trim(ucwords(strtolower($_POST["nombres"])));
    $apellidom = trim(ucfirst(strtolower($_POST["apellidom"])));
    $apellidop = trim(ucfirst(strtolower($_POST["apellidop"])));
    // $trabajador = trim($_POST["trabajador"]);
    $nombreEmpleado = $apellidop . " " . $apellidom . " " . $nombres;

    $sql = "SELECT * FROM Usuario WHERE RFC = '".$RFC."'";
    $consulta = mysqli_query($conexion, $sql);
    $res = mysqli_fetch_array($consulta);
    $puesto_anterior = $res["puesto"];

    $sql = "UPDATE Usuario SET 
    id_usuario = " . $id_usuario . ",
    nombre = '" . $nombreEmpleado . "', CURP = '" . $CURP . "',
    banca = NULLIF('" . $banca . "', ''),
    afiliacion = NULLIF('" . $afiliacion . "',''),
    nombres = '" . $nombres . "', apellidop = '" . $apellidop . "',
    apellidom = '" . $apellidom . "',
    fechaRelLab = '" . $ingreso . "' 
    WHERE RFC = '" . $RFC . "'";

    if (mysqli_query($conexion, $sql)) {
        echo 1;
    } else {
        echo 0;
    }

    mysqli_close($conexion);
}
