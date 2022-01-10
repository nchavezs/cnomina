<?php
session_start();
include "conexion.php";
include "rol.php";
$rol = rol();
if ($rol != 1) {
    echo 2;
} else {
    $conexion = conexion();
    $id = $_POST['id'];

    $sql = "DELETE FROM Usuario WHERE RFC = '" . $id . "' AND categoria = 'user'";
    if (mysqli_query($conexion, $sql)) {
        $sql = "DELETE FROM Movimiento WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Baja WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Descuento WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Permiso WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Vacacion WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Beneficiario WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Chat WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Mensaje WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Archivo WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Pase WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Reingreso WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "UPDATE Plaza SET RFC = NULL WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Historial_Plaza WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        $sql = "DELETE FROM Correo WHERE RFC = '" . $id . "'";
        mysqli_query($conexion, $sql);
        echo 1;
    } else {
        echo 0;
    }

    mysqli_close($conexion);
}
