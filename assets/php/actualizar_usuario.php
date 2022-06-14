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
        $sql = "DELETE FROM Autorizacion_Usuario WHERE RFC = '".$id."'";
        $conexion->query($sql);

        $sql = "UPDATE Rol_Usuario SET id_rol = ".$rol.", RFC = '".$alias."' WHERE RFC = '".$id."'";

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
        echo "Error al actualizar el usuario.";
    }
} else {
    echo "Ya existe un usuario con este alias.";
}

$conexion->close();