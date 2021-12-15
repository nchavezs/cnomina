<?php
include "conexion.php";
$conexion = conexion();
session_start();
$id = $_POST['id'];
$sql = "DELETE FROM Beneficiario WHERE id_beneficiario = " . $id;
mysqli_query($conexion, $sql);
mysqli_close($conexion);

//eliminar archivos