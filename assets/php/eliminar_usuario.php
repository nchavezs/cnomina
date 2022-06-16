<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "DELETE FROM Usuario WHERE RFC = '".$id."'";
if($conexion->query($sql)){
    $sql = "DELETE FROM Autorizacion_Usuario WHERE RFC = '".$id."'";
    $conexion->query($sql);
    $sql = "DELETE FROM Rol_Usuario WHERE RFC = '".$id."'";
    $conexion->query($sql);
    
    echo 1;
}else{
    echo "Error al eliminar usuario.";
}

$conexion->close();
