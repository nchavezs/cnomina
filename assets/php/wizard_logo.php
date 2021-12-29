<?php
include "conexion.php";
$conexion = conexion();

$archivo = $_FILES['file']['tmp_name'];
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$ruta = "../img/logo.".$ext;
move_uploaded_file($archivo, $ruta);

$sql = "UPDATE Configuracion SET logo = 'logo.".$ext."?v=".uniqid()."'";
mysqli_query($conexion, $sql);
mysqli_close($conexion);
