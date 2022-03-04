<?php
include "conexion.php";
$conexion = conexion();
session_start();
$id = $_POST['id'];
$sql = "DELETE FROM Beneficiario WHERE id_beneficiario = " . $id;
$conexion->query($sql);
$conexion->close();

//eliminar archivos