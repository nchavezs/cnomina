<?php
session_start();
include "conexion.php";
include "rol.php";
$rol = rol();
if ($rol != 1) {
    echo 2;
} else {
    $conexion = conexion();
    // $id_empleado = trim($_POST["id"]);
    // $ingreso = trim($_POST["ingreso"]);
    $RFC = trim($_POST["rfc"]);
    $CURP = trim($_POST["curp"]);
    // $puesto = trim($_POST["puesto"]);
    // $departamento = trim($_POST["departamento"]);
    $banca = trim($_POST["banca"]);
    $afiliacion = trim($_POST["afiliacion"]);
    $nombres = trim(ucwords(mb_strtolower($_POST["nombres"])));
    $apellidom = trim(ucfirst(mb_strtolower($_POST["apellidom"])));
    $apellidop = trim(ucfirst(mb_strtolower($_POST["apellidop"])));
    $trabajador = $_POST["trabajador"];
    $nombreEmpleado = $apellidop . " " . $apellidom . " " . $nombres;
    $periodo = $_SESSION["id_periodo"];

    $sql = "SELECT * FROM Usuario WHERE RFC = '" . $RFC . "'";
    $consulta = mysqli_query($conexion, $sql);
    $res = mysqli_fetch_array($consulta);
    // $puesto_anterior = $res["puesto"];

    $sql = "UPDATE Usuario SET nombre = '" . $nombreEmpleado . "' WHERE RFC = '" . $RFC . "'";

    if (mysqli_query($conexion, $sql)) {

        $sql = "UPDATE Empleado SET 
        CURP = '" . $CURP . "',
        banca = NULLIF('" . $banca . "', ''),
        afiliacion = NULLIF('" . $afiliacion . "',''),
        nombres = '" . $nombres . "', 
        apellidop = '" . $apellidop . "',
        apellidom = '" . $apellidom . "',
        id_trabajador = '" . $trabajador . "',
        id_periodo = " . $periodo . " 
        WHERE RFC = '" . $RFC . "'";
        if(mysqli_query($conexion, $sql)){
            echo 1;
        }else{
            echo 0;
        }
    } else {
        echo 0;
    }

    mysqli_close($conexion);
}
