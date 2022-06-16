<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "UPDATE Usuario SET estado = 'baja', contrasenia = '".uniqid()."' WHERE RFC = '".$id."'";
if($conexion->query($sql)){
    echo 1;
}else{
    echo "Error al dar de baja al usuario.";
}

$conexion->close();
