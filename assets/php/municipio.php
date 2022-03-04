<?php

function get_municipio()
{
    $conexion = conexion();
    $sql = "SELECT * FROM Configuracion";
    $consulta = $conexion->query($sql);
    $res = mysqli_fetch_array($consulta);
    $conexion->close();
    return $res["nombre"];
}

function get_logo()
{
    $conexion = conexion();
    $sql = "SELECT * FROM Configuracion";
    $consulta = $conexion->query($sql);
    $res = mysqli_fetch_array($consulta);
    $conexion->close();
    return "assets/img/".$res["logo"];
}
