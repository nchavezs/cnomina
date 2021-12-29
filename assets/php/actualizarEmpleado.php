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
    $puesto = trim($_POST["puesto"]);
    $departamento = trim($_POST["departamento"]);
    $banca = trim($_POST["banca"]);
    $afiliacion = trim($_POST["afiliacion"]);
    $nombres = trim(ucwords(strtolower($_POST["nombres"])));
    $apellidom = trim(ucfirst(strtolower($_POST["apellidom"])));
    $apellidop = trim(ucfirst(strtolower($_POST["apellidop"])));
    $trabajador = trim($_POST["trabajador"]);
    $nombreEmpleado = $apellidop . " " . $apellidom . " " . $nombres;

    // if ($archivo !== "") {
    //     $sql = "SELECT RFC FROM Usuario WHERE archivo = '" . $archivo . "'";
    //     $consulta = mysqli_query($conexion, $sql);
    //     $res = mysqli_num_rows($consulta);

    //     if ($res == 0) {
    //         $file = explode("/", $archivo);
    //         $de = "./../archivos/" . $file[2];
    //         $a = "./../usuario/" . $file[2];
    //         copy($de, $a);

    //         $files = glob('./../archivos/*');
    //         foreach ($files as $file) {
    //             if (is_file($file)) {
    //                 unlink($file);
    //             }

    //         }
    //     }
    // }

    $sql = "SELECT * FROM Usuario WHERE RFC = '".$RFC."'";
    $consulta = mysqli_query($conexion, $sql);
    $res = mysqli_fetch_array($consulta);
    $puesto_anterior = $res["puesto"];

    $sql = "UPDATE Usuario SET id_usuario = " . $id_usuario . ", nombre = '" . $nombreEmpleado . "', CURP = '" . $CURP . "',
    puesto = '" . $puesto . "', departamento = '" . $departamento . "', banca = NULLIF('" . $banca . "', ''),
    afiliacion = NULLIF('" . $afiliacion . "',''), nombres = '" . $nombres . "', apellidop = '" . $apellidop . "',
    apellidom = '" . $apellidom . "',
    tipoTrabajador = '" . $trabajador . "',
    fechaRelLab = '" . $ingreso . "' 
    WHERE RFC = '" . $RFC . "'";

    // archivo = NULLIF('" . $archivo . "', '')

    if (mysqli_query($conexion, $sql)) {
        $sql = "UPDATE Puesto SET ocupado = (ocupado - 1)  WHERE nombre = '".$puesto_anterior."'";
        mysqli_query($conexion,$sql);
        $sql = "UPDATE Puesto SET ocupado = (ocupado + 1)  WHERE nombre = '".$puesto."'";
        mysqli_query($conexion,$sql);

        echo 1;
    } else {
        echo 0;
    }

    mysqli_close($conexion);
}
