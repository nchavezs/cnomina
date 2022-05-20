<?php
session_start();
$myid = $_SESSION["usuario"];
include "conexion.php";
$conexion = conexion();
$sql = "SELECT * FROM Mensaje WHERE receptor like '%" . $myid . "%' AND estado = 0";
$consulta = $conexion->query($sql);
$total = mysqli_num_rows($consulta);
echo $total;
$conexion->close();
