<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql1 = "SELECT * FROM Usuario WHERE RFC = '".$id."'";
$consulta1 = mysqli_query($conexion, $sql1);
$res = mysqli_fetch_array($consulta1);
$usuario = str_pad($res['id_usuario'], 5, '0', STR_PAD_LEFT);
$sql2 = "UPDATE Usuario SET contrasenia = '" . $usuario . "' WHERE RFC = '" . $id . "'";

mysqli_query($conexion, $sql2);
$conexion->close();
