<?php
include "conexion.php";
$conexion = conexion();
$id_puesto = $_POST["id_puesto"];
$estado = $_POST["estado"];

function diferencia($fecha1, $fecha2)
{
    $fecha1 = new DateTime($fecha1);
    $fecha2 = new DateTime($fecha2);
    $diff = $fecha2->diff($fecha1);
    return $diff->format('%a');
}

if ($estado == 1) {
    $estado = "AND RFC IS NOT NULL";
} else if ($estado == 2) {
    $estado = "AND RFC IS NULL";
} else {
    $estado = "";
}

if ($id_puesto == 0) {
    $id_puesto = "ANY(SELECT id_puesto FROM Puesto)";
}

$sql = "SELECT * FROM Plaza WHERE id_puesto = " . $id_puesto . " " . $estado;
$resultado = $conexion->query($sql);
if ($resultado && (mysqli_num_rows($resultado) == 0)) {
    echo '{"data":[]}';
} else {
    $ano = date("Y");
    $hoy = date("Y-m-d");
    while ($res = mysqli_fetch_array($resultado)) {
        // ------------------------------------------------------------------------------------------------------
        $sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $res["RFC"] . "'";
        $consulta = $conexion->query($sql);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $usuario = mysqli_fetch_row($consulta);
        } else {
            $usuario[0] = '<span class="alta">POR EJERCER</span>';
        }

        $sql = "SELECT nombre,id_departamento FROM Puesto WHERE id_puesto = " . $res["id_puesto"];
        $consulta = $conexion->query($sql);
        $puesto = mysqli_fetch_row($consulta);

        $sql = "SELECT nombre FROM Departamento WHERE id_departamento = " . $puesto[1];
        $consulta = $conexion->query($sql);
        $departamento = mysqli_fetch_row($consulta);

        $ocupados = 0;
        $ocupados_total = 0;

        $fin_ano = date("Y-m-d", strtotime($ano . "-12-31"));

        $vacantes = diferencia($fin_ano, $hoy);
        if ($vacantes >= $res["dias"]) {
            $vacantes = $res["dias"];
        }

        $sql = "SELECT * FROM Historial_Plaza WHERE id_plaza = " . $res["id_plaza"] . " AND YEAR(fecha_inicio) = " . $ano;
        $consulta = $conexion->query($sql);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            while ($historial = mysqli_fetch_array($consulta)) {
                $fecha1 = $historial["fecha_inicio"];
                if ($historial["fecha_fin"] != null) {
                    $fecha2 = $historial["fecha_fin"];
                } else {
                    $fecha2 = $hoy;
                }
                $ocupados = diferencia($fecha1, $fecha2);
                $ocupados_total = $ocupados_total + $ocupados + 1;
            }
            // $vacantes = $res["dias"] - $ocupados_total;
        }
        $desocupados = $res["dias"] - $vacantes - $ocupados_total;
        if ($desocupados < 0) {
            $desocupados = 0;
        }
        // ------------------------------------------------------------------------------------------------------

        $res['usuario'] = $usuario[0];
        $res['puesto'] = $puesto[0];
        $res['departamento'] = $departamento[0];
        $res['vacantes'] = $vacantes;
        $res["ocupados"] = $ocupados_total;
        $res["desocupados"] = $desocupados;
        $arreglo["data"][] = $res;
    }
    echo json_encode($arreglo);
}
$conexion->close();
