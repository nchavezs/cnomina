<?php
include "conexion.php";
$conexion = conexion();


$opciones = [];
$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    while ($res = mysqli_fetch_assoc($consulta)) {
        $opciones[$res['id_departamento']] = $res['nombre'];
    }
} 
    
echo json_encode($opciones);

mysqli_close($conexion);
