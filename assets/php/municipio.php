<?php

function get_municipio()
{
    $conexion = conexion();
    $sql = "SELECT * FROM Configuracion";
    $consulta = mysqli_query($conexion, $sql);
    $res = mysqli_fetch_array($consulta);
    mysqli_close($conexion);
    return $res["nombre"];
}

function get_logo()
{
    $conexion = conexion();
    $sql = "SELECT * FROM Configuracion";
    $consulta = mysqli_query($conexion, $sql);
    $res = mysqli_fetch_array($consulta);
    mysqli_close($conexion);
    return "assets/img/".$res["logo"];
}
