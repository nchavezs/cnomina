<?php
include "conexion.php";
$conexion = conexion();

$id_usuario = trim($_POST["numero"]);
// $archivo = trim($_POST["archivo"]);
$RFC = mb_strtoupper(trim($_POST["rfc"]));
$CURP = mb_strtoupper(trim($_POST["curp"]));
$fechaRelLab = trim($_POST["ingreso"]);
$puesto = $_POST["puesto"];
$departamento = $_POST["departamento"];
$banca = trim($_POST["banca"]);
$afiliacion = trim($_POST["afiliacion"]);
$nombres = trim(ucwords(mb_strtolower($_POST["nombres"])));
$apellidom = trim(ucfirst(mb_strtolower($_POST["apellidom"])));
$apellidop = trim(ucfirst(mb_strtolower($_POST["apellidop"])));
$trabajador = $_POST["trabajador"];
$nombreEmpleado = $apellidop . " " . $apellidom . " " . $nombres;
$password = str_pad($id_usuario, 5, '0', STR_PAD_LEFT);
$plaza = $_POST["plaza"];
$periodo = $_POST["periodo"];

$sql = "SELECT RFC FROM Usuario WHERE RFC = '" . $RFC . "'";
$consulta = mysqli_query($conexion, $sql);
if (mysqli_num_rows($consulta) == 0) {
    $sql = "INSERT INTO Usuario(id_usuario, categoria, contrasenia, nombre, rfc, curp, fechaRelLab, puesto,
    departamento, banca, afiliacion, apellidop, apellidom, nombres, tipoTrabajador, id_periodo) VALUES(
    " . $id_usuario . ",
    'user',
    '" . $password . "',
    '" . $nombreEmpleado . "',
    '" . $RFC . "',
    '" . $CURP . "',
    '" . $fechaRelLab . "',
    '" . $puesto . "',
    '" . $departamento . "',
    NULLIF('" . $banca . "', ''),
    NULLIF('" . $afiliacion . "',''),
    '" . $apellidop . "' ,
    '" . $apellidom . "',
    '" . $nombres . "',
    '" . $trabajador . "',
    " . $periodo . ")";

    if (mysqli_query($conexion, $sql)) {
        $sql = "UPDATE Plaza SET RFC = '" . $RFC . "' WHERE id_plaza = " . $plaza;
        $consulta = mysqli_query($conexion, $sql);
        $sql = "INSERT INTO Historial_Plaza(id_plaza, fecha_inicio, RFC)
        VALUES(" . $plaza . ", STR_TO_DATE('" . $fechaRelLab . "','%d/%m/%Y'), '" . $RFC . "')";
        $consulta = mysqli_query($conexion, $sql);

        echo 0;
    } else {
        echo 1;
    }
} else {
    echo 2;
}

mysqli_close($conexion);
