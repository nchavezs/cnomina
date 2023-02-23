<?php
session_start();
include "conexion.php";

$conexion = conexion();
// $id_empleado = trim($_POST["id"]);
// $ingreso = trim($_POST["ingreso"]);
$RFC = trim($_POST["rfc"]);
$CURP = trim($_POST["curp"]);
// $puesto = trim($_POST["puesto"]);
// $departamento = trim($_POST["departamento"]);
$domicilio = mb_strtoupper(trim($_POST["domicilio"]));
$email = trim($_POST["email"]);
$banca = trim($_POST["banca"]);
$afiliacion = trim($_POST["afiliacion"]);
$postal = trim($_POST["postal"]);
$telefono = trim($_POST["telefono"]);
$nombres = trim(ucwords(mb_strtolower($_POST["nombres"])));
$apellidom = trim(ucfirst(mb_strtolower($_POST["apellidom"])));
$apellidop = trim(ucfirst(mb_strtolower($_POST["apellidop"])));
$nombreEmpleado = $apellidop . " " . $apellidom . " " . $nombres;
$periodo = $_SESSION["id_periodo"];

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $RFC . "'";
$consulta = $conexion->query($sql);
$res = mysqli_fetch_array($consulta);

$sql = "UPDATE Usuario SET 
nombre = '" . $nombreEmpleado . "',
domicilio = '".$domicilio."',
postal = '".$postal."',
telefono = '".$telefono."',
email = '".$email."' 
WHERE RFC = '" . $RFC . "'";

if ($conexion->query($sql)) {

    $sql = "UPDATE Empleado SET
    CURP = '" . $CURP . "',
    banca = NULLIF('" . $banca . "', ''),
    afiliacion = NULLIF('" . $afiliacion . "',''),
    nombres = '" . $nombres . "',
    apellidop = '" . $apellidop . "',
    apellidom = '" . $apellidom . "',
    id_periodo = " . $periodo . " 
    WHERE RFC = '" . $RFC . "'";
    if ($conexion->query($sql)) {
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 0;
}

$conexion->close();