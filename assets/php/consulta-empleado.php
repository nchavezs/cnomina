<?php
include "conexion.php";
$conexion = conexion();
$estado = $_POST["estado"];

$sql = "SELECT
   Empleado.RFC AS RFC,
   nombre,
   (SELECT nombre FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto) AS puesto,
   (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto)) AS departamento,
   (SELECT nombre FROM Trabajador WHERE Trabajador.id_trabajador = Empleado.id_trabajador) AS tipoTrabajador,
   id_empleado
   FROM Empleado LEFT JOIN Usuario ON Empleado.RFC = Usuario.RFC WHERE estado = '" . $estado . "'";

$resultado = mysqli_query($conexion, $sql);
if (mysqli_num_rows($resultado) == 0) {
    echo '{"data":[]}';
} else {
    while ($res = mysqli_fetch_array($resultado)) {
        $res["id_empleado"] = str_pad($res["id_empleado"], 5, '0', STR_PAD_LEFT);
        if ($res["tipoTrabajador"] == null) {
            $res["tipoTrabajador"] = "N/A";
        }

        $arreglo["data"][] = $res;
    }
    echo json_encode($arreglo);
}

mysqli_close($conexion);
