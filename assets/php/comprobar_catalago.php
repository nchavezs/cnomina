<?php
$conexion = conexion();

$omitir = $_GET["pass"] ?? null;

if (rol() == 1) {
    $sql = "SELECT * FROM Puesto";
    $resultado = mysqli_query($conexion, $sql);
    if (mysqli_num_rows($resultado) == 0 && $omitir == null) {
        header("location: ./configuracion");
    }
}
