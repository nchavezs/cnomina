<?php
include "conexion.php";
$conexion = conexion();
$nombre = trim(mb_strtoupper($_POST['nombre']));
$autorizaciones = $_POST['autorizaciones'] ?? [];

if(sizeof($autorizaciones) > 0){
    $sql = "SELECT * FROM Rol WHERE nombre = '" . $nombre . "'";
    $consulta = $conexion->query($sql);
    if ($consulta && (mysqli_num_rows($consulta) == 0)) {
        $sql = "INSERT INTO Rol(nombre) VALUES('" . $nombre . "')";
        if ($conexion->query($sql)) {
            $rol = mysqli_insert_id($conexion);
    
            foreach($autorizaciones as $autorizacion){
                $sql = "INSERT INTO Rol_Autorizacion(id_autorizacion, id_rol) VALUES(".$autorizacion.", ".$rol.")";
                $conexion->query($sql);
            }
    
    
            echo 1;
        } else {
            echo "Error al registrar el rol.";
        }
    } else {
        echo "Ya existe un rol con este nombre.";
    }
    
    $conexion->close();
}else{
    echo "Asigna al menos un permiso";
}