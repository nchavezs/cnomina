<?php
$conexion = conexion();

if($rol == 1){
    $sql = "SELECT * FROM Puesto";
    $resultado = mysqli_query($conexion, $sql);
    $total = mysqli_num_rows($resultado);
    if($total == 0){
        header("location: ./configuracion");
    }
}