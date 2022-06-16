<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "UPDATE Usuario SET contrasenia = '".$id."' WHERE RFC = '".$id."'";
if($conexion->query($sql)){
    echo 1;
}else{
    echo "Error al restablecer contraseña de usuario.";
}

$conexion->close();
