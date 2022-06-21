<?php
include "conexion.php";
$conexion = conexion();
$nombre = trim(mb_strtoupper($_POST['nombre']));
$alias = trim($_POST['alias']);
$email = trim($_POST['email']);
$rol = $_POST['rol'];



$sql = "SELECT * FROM Usuario WHERE RFC = '" . $alias . "'";
$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta) == 0)) {
    $sql = "INSERT INTO Usuario(nombre, RFC, email, categoria, contrasenia) VALUES(
        '" . $nombre . "',
        '".$alias."',
        '".$email."',
        'admin',
        '".$alias."'
    )";
    
    if ($conexion->query($sql)) {
        $sql = "INSERT INTO Rol_Usuario(id_rol, RFC) VALUES(".$rol.", '".$alias."')";
        if($conexion->query($sql)){
            echo 1;
        }else{
            echo "Error al asignar rol.";
        }

    } else {
        echo "Error al registrar el usuario.";
    }
} else {
    echo "Ya existe un usuario con este alias.";
}

$conexion->close();