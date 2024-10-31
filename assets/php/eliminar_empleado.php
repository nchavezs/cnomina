<?php
session_start();
include "conexion.php";

$conexion = conexion();
$id = $_POST['id'];

mysqli_autocommit($conexion, false);
$errors = [];


$sql1 = "DELETE FROM Usuario WHERE RFC = '" . $id . "' AND categoria = 'user'";

$sql2 = "DELETE FROM Empleado WHERE RFC = '" . $id . "'";

$sql3 = "UPDATE Plaza SET RFC = NULL WHERE RFC = '" . $id . "'";

$sql4 = "DELETE FROM Historial WHERE RFC = '" . $id . "'";

if (!$conexion->query($sql1)) {
    $errors[] = $conexion->error;
}
if (!$conexion->query($sql2)) {
    $errors[] = $conexion->error;
}
if (!$conexion->query($sql3)) {
    $errors[] = $conexion->error;
}

if (!$conexion->query($sql4)) {
    $errors[] = $conexion->error;
}

if (count($errors) === 0) {
    $conexion->commit();
    echo 1;
} else {
    $conexion->rollback();
    echo 0;
}

$conexion->close();
