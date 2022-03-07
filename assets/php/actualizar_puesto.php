<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$nombre = trim(mb_strtoupper($_POST['nombre']));
$id_departamento = $_POST['departamento'];
$id_trabajador = $_POST['trabajador'];

$sql = "SELECT * FROM Puesto WHERE nombre = '".$nombre."' AND id_departamento = ".$id_departamento." AND id_puesto NOT IN(".$id.")";
$consulta = $conexion->query($sql);

if(mysqli_num_rows($consulta) == 0){
    $puesto = mysqli_fetch_array($consulta);
    $sql = "UPDATE Puesto SET 
    nombre = '".$nombre."', 
    id_departamento = ".$id_departamento.",
    id_trabajador = ".$id_trabajador." 
    WHERE id_puesto = " . $id;
    
    if($conexion->query($sql)){
        echo 1;
    }else{
        echo 0;
    }
}else{
    echo 2;
}

$conexion->close();
