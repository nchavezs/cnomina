<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$myid = $_SESSION['usuario'];

$sql = "SELECT * FROM Mensaje WHERE 
emisor = '".$myid."' AND 
receptor = '".$id."'";

$consulta = mysqli_query($conexion, $sql);
if($consulta && mysqli_num_rows($consulta) > 0){
    echo "";
}else{
    echo "SIN NADA";
}


mysqli_close($conexion);