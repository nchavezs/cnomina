<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$id_prenomina = $_SESSION["id_prenomina"];

$sql = "SELECT RFC FROM Usuario WHERE RFC = '" . $id . "' AND estado = 'baja'";
$consulta = $conexion->query($sql);
if ($consulta && mysqli_num_rows($consulta) > 0) {
    $titulo = "Usuario dado de baja";
    $mensaje = '<p class="pb-3">Este usuario se encuentra actualmente dado de baja por lo que no podrá realizar nuevos registros.</p>';
} else {
    $sql = "SELECT * FROM Prenomina WHERE estado = 1 AND id_prenomina = ".$id_prenomina;
    $consulta = $conexion->query($sql);
    if ($consulta && mysqli_num_rows($consulta) > 0) {
        $titulo = "Periodo cerrado";
        $mensaje = '<p class="pb-3">El periodo actual se encuentra cerrado imposible realizar nuevos registros.</p>';
    }else{
        $success = true;
    }
}

$datos["titulo"] = $titulo ?? "";
$datos["mensaje"] = $mensaje ?? "";
$datos["success"] = $success ?? false;
echo json_encode($datos);

$conexion->close();