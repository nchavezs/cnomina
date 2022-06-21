<?php
include "conexion.php";
$conexion = conexion();
$nombre = trim(mb_strtoupper($_POST['nombre']));
$alias = trim($_POST['alias']);
$email = trim($_POST['email']);
$rol = $_POST['rol'];
$id = $_POST['id'];

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $alias . "' AND RFC NOT IN('".$id."')";
$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta) == 0)) {
    $sql = "UPDATE Usuario SET 
        nombre = '" . $nombre . "',
        RFC = '".$alias."',
        email = '".$email."'
    WHERE RFC = '".$id."'";
    
    if ($conexion->query($sql)) {
        $sql = "UPDATE Rol_Usuario SET id_rol = ".$rol.", RFC = '".$alias."' WHERE RFC = '".$id."'";
        if($conexion->query($sql)){
            echo 1;
        }else{
            echo "Error al asignar rol.";
        }

    } else {
        echo "Error al actualizar el usuario.";
    }
} else {
    echo "Ya existe un usuario con este alias.";
}

$conexion->close();