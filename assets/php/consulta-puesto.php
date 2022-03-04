<?php
include "conexion.php";
$conexion = conexion();

$sql = "SELECT 
Puesto.id_puesto, 
Puesto.nombre AS puesto,
(SELECT COUNT(*) FROM Plaza WHERE id_puesto = Puesto.id_puesto AND estado = 1) AS plazas,
(SELECT nombre FROM Departamento WHERE id_departamento = Puesto.id_departamento) AS departamento, 
(SELECT nombre FROM Trabajador WHERE id_trabajador = Puesto.id_trabajador) AS categoria 
FROM Puesto";

$resultado = $conexion->query($sql);
if ($resultado && (mysqli_num_rows($resultado) == 0)) {
    echo '{"data":[]}';
} else {
    $i = 1;
    while ($res = mysqli_fetch_array($resultado)) {
        $sql = "SELECT COUNT(*) FROM Plaza WHERE id_puesto = ".$res["id_puesto"];
        $consulta = $conexion->query($sql);
        $plazas = mysqli_fetch_row($consulta);
        $res['numero'] = $i++;
        $arreglo["data"][] = $res;
    }   
    echo json_encode($arreglo);
}
$conexion->close();