<?php
include "conexion.php";
$conexion = conexion();

$sql = "SELECT * FROM Prenomina LEFT JOIN Periodo ON Prenomina.id_periodo = Periodo.id_periodo ORDER BY al DESC";
$resultado = mysqli_query($conexion, $sql);
if (mysqli_num_rows($resultado) == 0) {
    echo '{"data":[]}';
} else {
    while ($res = mysqli_fetch_array($resultado)) {
        $res["elaboracion"] = date("d/m/Y h:i A", strtotime($res["elaboracion"]));
        $res["del"] = date("d/m/Y", strtotime($res["del"]));
        $res["al"] = date("d/m/Y", strtotime($res["al"]));
        $arreglo["data"][] = $res;
    }
    echo json_encode($arreglo);
}

mysqli_close($conexion);
