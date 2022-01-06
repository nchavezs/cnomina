<?php
include "conexion.php";
$conexion = conexion();

$sql = "SELECT Puesto.id_puesto, Puesto.nombre AS puesto, Departamento.nombre AS 'departamento' FROM Puesto 
JOIN Departamento ON Puesto.id_departamento = Departamento.id_departamento";
$resultado = mysqli_query($conexion, $sql);
if ($resultado && (mysqli_num_rows($resultado) == 0)) {
    echo '{"data":[]}';
} else {
    $i = 1;
    while ($res = mysqli_fetch_array($resultado)) {
        $sql = "SELECT COUNT(*) FROM Plaza WHERE id_puesto = ".$res["id_puesto"];
        $consulta = mysqli_query($conexion, $sql);
        $plazas = mysqli_fetch_row($consulta);
        $res['numero'] = $i++;
        $res['plazas'] = $plazas[0];
        $arreglo["data"][] = $res;
    }   
    echo json_encode($arreglo);
}
mysqli_close($conexion);