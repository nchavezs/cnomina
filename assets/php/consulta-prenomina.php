<?php
setlocale(LC_ALL, "spanish");

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
$total = mysqli_num_rows($resultado);
if (mysqli_num_rows($resultado) == 0) {
    echo '{"data":[]}';
} else {
    $i = $total;
    while ($res = mysqli_fetch_array($resultado)) {
        $res["numero"] = $i;
        // $res["elaboracion"] = date("Y-m-d H:i:s", strtotime($res["elaboracion"]));
        $res["del"] = strftime("%d %b", strtotime($res["del"]));
        $res["al"] = strftime("%d %b", strtotime($res["al"]));
        $arreglo["data"][] = $res;
        $i--;
    }
    echo json_encode($arreglo);
}

mysqli_close($conexion);
