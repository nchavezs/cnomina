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
            $sql = "SELECT * FROM Rol_Autorizacion WHERE id_rol = ".$rol;
            $consulta = $conexion->query($sql);
            while($autorizacion = mysqli_fetch_array($consulta)){
                $sql = "INSERT INTO Autorizacion_Usuario(id_autorizacion, RFC) VALUES(".$autorizacion["id_autorizacion"].", '".$alias."')";
                $conexion->query($sql);
            }
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