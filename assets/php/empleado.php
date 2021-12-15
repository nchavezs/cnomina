<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "' AND categoria = 'user'";

if ($resultado = mysqli_query($conexion, $sql)) {
    while ($res = mysqli_fetch_array($resultado)) {
        $datos["id"] = $res[0];
        $datos["nombre"] = $res[6];
        $datos["ingreso"] = $res[10];
        $datos["banca"] = $res[13];
        $datos["afiliacion"] = $res[14];
        $datos["puesto"] = $res[11];
        $datos["departamento"] = $res[12];
        $datos["curp"] = $res[9];
        $datos["rfc"] = $res[8];
    }
    echo json_encode($datos);
} else {
    echo 0;
}

mysqli_close($conexion);
