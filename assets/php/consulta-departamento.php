<?php
session_start();
include "rol.php";
include "conexion.php";
$conexion = conexion();

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

$sql = "SELECT id_departamento, nombre FROM Departamento";
$resultado = $conexion->query($sql);
if ($resultado && (mysqli_num_rows($resultado) == 0)) {
    echo '{"data":[]}';
} else {
    $i = 1;
    while ($res = mysqli_fetch_array($resultado)) {
        $res['numero'] = $res["id_departamento"];

        if ($bandera_eliminar) {
            $eliminar = "eliminar(" . $res["id_departamento"] . ",'Departamento')";
        } else {
            $eliminar = "bloqueo(event)";
        }

        if ($bandera_editar) {
            $editar = "editar_departamento(" . $res['id_departamento'] . ")";
        } else {
            $editar = "bloqueo(event)";
        }

        $res["opciones"] = '
        <i class="material-icons btn1" onclick="' . $editar . '" >editar</i>
        <i class="material-icons btn1-danger" onClick="' . $eliminar . '">delete</i>';

        $arreglo["data"][] = $res;
    }
    echo json_encode($arreglo);
}
$conexion->close();
