<?php
$conexion = conexion();

$omitir = $_GET["pass"] ?? null;

if (rol() == 1) {
    $sql = "SELECT * FROM Puesto";
    $resultado = $conexion->query($sql);
    if (mysqli_num_rows($resultado) == 0 && $omitir == null) {
        header("location: ./configuracion");
    }
}
