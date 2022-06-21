<?php
include "conexion.php";
$conexion = conexion();
$nombre = trim(mb_strtoupper($_POST['nombre']));
$autorizaciones = $_POST['autorizaciones'];
$id = $_POST['id'];

$sql = "SELECT * FROM Rol WHERE nombre = '" . $nombre . "' AND id_rol NOT IN(".$id.")";
$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta) == 0)) {
    $sql = "UPDATE Rol SET nombre = '" . $nombre . "' WHERE id_rol = ".$id;
    if ($conexion->query($sql)) {
        $sql = "DELETE FROM Rol_Autorizacion WHERE id_rol = ".$id;
        if($conexion->query($sql)){
            foreach($autorizaciones as $autorizacion){
                $sql = "INSERT INTO Rol_Autorizacion(id_autorizacion, id_rol) VALUES(".$autorizacion.", ".$id.")";
                $conexion->query($sql);
            }
    
            echo 1;
        }
    } else {
        echo "Error al actualizar el rol.";
    }
} else {
    echo "Ya existe un rol con este nombre.";
}

$conexion->close();