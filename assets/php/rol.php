<?php

function rol(){
    $RFC = $_SESSION['usuario'];
    $conexion = conexion();
    $sql = "SELECT rol FROM Roles WHERE RFC = '" . $RFC . "'";
    $consulta = $conexion->query($sql);
    $res = mysqli_fetch_row($consulta);
    $conexion->close();
    return $res[0];
}