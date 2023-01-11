<?php
$conexion = conexion();

$omitir = $_GET["pass"] ?? null;
$ano = $_SESSION["ano"];
$sql = "SELECT * FROM Plaza WHERE YEAR(elaboracion) = ".$ano;
$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) == 0 && $omitir == null) {
    header("location: ./configuracion_plaza");
}
