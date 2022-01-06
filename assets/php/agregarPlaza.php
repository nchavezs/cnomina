<?php
include "conexion.php";
$conexion = conexion();
$dias = $_POST['dias'];
$puesto = $_POST['puesto'];
$cantidad = $_POST['cantidad'];

$sql = "INSERT INTO Plaza(id_puesto, dias) VALUES(" . $puesto . ", " . $dias . ")";
for($i=0;$i<$cantidad;$i++){
    $consulta = mysqli_query($conexion, $sql);
}
if ($consulta) {
    echo 1;
} else {
    echo 0;
}

mysqli_close($conexion);
