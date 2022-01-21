<?php
include "conexion.php";
$conexion = conexion();
$ano = $_POST["ano"];
$id_periodo = $_POST["id_periodo"];

$sql = "SELECT * FROM Prenomina 
LEFT JOIN Periodo ON Prenomina.id_periodo = Periodo.id_periodo WHERE 
YEAR(del) = ".$ano." AND 
Prenomina.id_periodo = ".$id_periodo." 
ORDER BY del DESC";

$resultado = mysqli_query($conexion, $sql);
if (mysqli_num_rows($resultado) == 0) {
    echo '{"data":[]}';
} else {
    while ($res = mysqli_fetch_array($resultado)) {
        $res["elaboracion"] = date("d M h:i A", strtotime($res["elaboracion"]));
        $res["del"] = date("d M", strtotime($res["del"]));
        $res["al"] = date("d M", strtotime($res["al"]));
        $arreglo["data"][] = $res;
    }
    echo json_encode($arreglo);
}

mysqli_close($conexion);
