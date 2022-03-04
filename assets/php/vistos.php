<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_SESSION['usuario'];

$sql = "UPDATE Mensaje SET estado = 1 WHERE id_chat = " . $_POST['chat'] . " AND receptor = '" . $id . "'";
if ($conexion->query($sql)) {
    echo 1;
} else {
    echo 0;
}

$conexion->close();