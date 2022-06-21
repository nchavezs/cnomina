<?php

function rol(){
    $RFC = $_SESSION['usuario'];
    $conexion = conexion();
    $sql = "SELECT id_rol FROM Rol_Usuario WHERE RFC = '".$RFC."'";
    $consulta = $conexion->query($sql);
    $res = mysqli_fetch_row($consulta);
    $rol = $res[0];
    $autorizaciones = [];

    if($rol == 1){
        $sql = "SELECT id_autorizacion FROM Autorizacion";
        $consulta = $conexion->query($sql);
    }else{
        $sql = "SELECT id_autorizacion FROM Rol_Autorizacion WHERE id_rol = (SELECT id_rol FROM Rol_Usuario WHERE RFC = '" . $RFC . "')";
        $consulta = $conexion->query($sql);
    }

    while($res = mysqli_fetch_row($consulta)){
        $autorizaciones[] = $res[0];
    }

    $conexion->close();
    return $autorizaciones;
}

function id_rol(){
    $RFC = $_SESSION['usuario'];
    $conexion = conexion();
    $sql = "SELECT id_rol FROM Rol_Usuario WHERE RFC = '".$RFC."'";
    $consulta = $conexion->query($sql);
    $res = mysqli_fetch_row($consulta);
    $rol = $res[0];

    return $rol;
}
