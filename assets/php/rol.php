<?php

function rol(){
    $RFC = $_SESSION['usuario'];
    $conexion = conexion();
    $sql = "SELECT rol FROM Roles WHERE RFC = '" . $RFC . "'";
    $consulta = mysqli_query($conexion, $sql);
    $res = mysqli_fetch_row($consulta);
    mysqli_close($conexion);
    return $res[0];
}