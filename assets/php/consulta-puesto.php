<?php
session_start();
include "rol.php";
include "conexion.php";
$conexion = conexion();
$ano = $_SESSION["ano"];

$sql = "SELECT 
Puesto.id_puesto, 
Puesto.nombre AS puesto,
(SELECT COUNT(*) FROM Plaza WHERE id_puesto = Puesto.id_puesto AND estado = 1 AND YEAR(elaboracion)= $ano) AS plazas,
(SELECT nombre FROM Departamento WHERE id_departamento = Puesto.id_departamento) AS departamento, 
(SELECT nombre FROM Trabajador WHERE id_trabajador = Puesto.id_trabajador) AS categoria 
FROM Puesto";

if (in_array(34, rol())) {
    $bandera_eliminar = true;
} else {
    $bandera_eliminar = false;
}

if (in_array(33, rol())) {
    $bandera_editar = true;
} else {
    $bandera_editar = false;
}

$resultado = $conexion->query($sql);
if ($resultado && (mysqli_num_rows($resultado) == 0)) {
    echo '{"data":[]}';
} else {
    $i = 1;
    while ($res = mysqli_fetch_array($resultado)) {
        $res['numero'] = $res["id_puesto"];

        if ($bandera_eliminar) {
            $eliminar = "eliminar(".$res["id_puesto"].",'Puesto')";
        } else {
            $eliminar = "bloqueo(event)";
        }

        if ($bandera_editar) {
            $editar = "editar_puesto(".$res['id_puesto'].")";
        } else {
            $editar = "bloqueo(event)";
        }

        $res["opciones"] = '
        <i class="material-icons btn1" onclick="'.$editar.'" >editar</i>
        <i class="material-icons btn1-danger" onClick="'.$eliminar.'">delete</i>';


        $arreglo["data"][] = $res;
    }   
    echo json_encode($arreglo);
}
$conexion->close();