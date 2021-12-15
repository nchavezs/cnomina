<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$fecha = $_POST['fecha'];
$observaciones = trim($_POST['observaciones']);

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "' AND estado = 'baja'";
if (($consulta = mysqli_query($conexion, $sql)) && (mysqli_num_rows($consulta) == 1)) {
	$resultado = mysqli_fetch_array($consulta);
	$inicio = $resultado['fechaRelLab'];
    $sql = "UPDATE Usuario SET estado = 'alta', fechaRelLab = '" . $fecha . "' WHERE RFC = '" . $id."'";
    if (mysqli_query($conexion, $sql)) {
        $sql = "INSERT INTO Reingreso(RFC, fecha, inicio, observaciones) 
		VALUES('" . $id . "', STR_TO_DATE('" . $fecha . "','%d/%m/%Y'), STR_TO_DATE('" . $inicio . "','%d/%m/%Y'), '" . $observaciones . "')";
        if (mysqli_query($conexion, $sql)) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }

} else {
    echo 0;
}

mysqli_close($conexion);
