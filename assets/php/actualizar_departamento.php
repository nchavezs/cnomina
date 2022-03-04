<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$nombre = trim(mb_strtoupper($_POST['nombre']));

$sql = "SELECT * FROM Departamento WHERE nombre = '".$nombre."' AND id_departamento NOT IN(".$id.")";
$consulta = $conexion->query($sql);

if(mysqli_num_rows($consulta) == 0){
    $departamento = mysqli_fetch_array($consulta);
    $sql = "UPDATE Departamento  SET nombre = '".$nombre."' WHERE id_departamento = " . $id;
    if($conexion->query($sql)){
        echo 1;
    }else{
        echo 0;
    }
}else{
    echo 2;
}

$conexion->close();
