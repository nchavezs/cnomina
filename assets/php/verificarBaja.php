<?php
include "conexion.php";
$conexion = conexion();
$id = explode("-", $_POST['id']);
$id = $id[0];

$sql = "SELECT RFC FROM Usuario WHERE RFC = '" . $id . "' AND estado = 'alta'";
$consulta = mysqli_query($conexion, $sql);
$total = mysqli_num_rows($consulta);
if ($consulta && $total > 0) {
    echo 1;
} else {
    echo 0;
}
