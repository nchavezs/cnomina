<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "DELETE FROM Rol WHERE id_rol = '".$id."'";
if($conexion->query($sql)){
    // $sql = "DELETE FROM Rol_Usuario WHERE RFC = '".$id."'";
    // if($conexion->query($sql)){
    //     echo 1;
    // }
    echo 1;
}else{
    echo "Error al eliminar rol.";
}

$conexion->close();
