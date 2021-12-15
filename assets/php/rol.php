<?php
function rol(){
    $RFC = $_SESSION['usuario'];
    include "conexion.php";
    $conexion = conexion();
    $sql = "SELECT rol FROM Roles WHERE RFC = '" . $RFC . "'";
    $consulta = mysqli_query($conexion, $sql);
    $rol = mysqli_fetch_row($consulta);
    mysqli_close($conexion);
    return $rol[0];
}