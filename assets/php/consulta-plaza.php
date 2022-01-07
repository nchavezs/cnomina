<?php
include "conexion.php";
$conexion = conexion();

$sql = "SELECT * FROM Plaza";
$resultado = mysqli_query($conexion, $sql);
if ($resultado && (mysqli_num_rows($resultado) == 0)) {
    echo '{"data":[]}';
} else {
    $ano = date("Y");
    $hoy = date("Y-m-d");
    while ($res = mysqli_fetch_array($resultado)) {
        // ------------------------------------------------------------------------------------------------------
        $sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $res["RFC"] . "'";
        $consulta = mysqli_query($conexion, $sql);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $usuario = mysqli_fetch_row($consulta);
        } else {
            $usuario[0] = "VACANTE";
        }

        $sql = "SELECT nombre,id_departamento FROM Puesto WHERE id_puesto = " . $res["id_puesto"];
        $consulta = mysqli_query($conexion, $sql);
        $puesto = mysqli_fetch_row($consulta);

        $sql = "SELECT nombre FROM Departamento WHERE id_departamento = " . $puesto[1];
        $consulta = mysqli_query($conexion, $sql);
        $departamento = mysqli_fetch_row($consulta);

        $ocupados = 0;
        $ocupados_total = 0;
        $vacantes = $res["dias"];

        $sql = "SELECT * FROM Historial_Plaza WHERE id_plaza = " . $res["id_plaza"] . " AND YEAR(fecha_inicio) = " . $ano;
        $consulta = mysqli_query($conexion, $sql);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            while ($historial = mysqli_fetch_array($consulta)) {
                $fecha1 = new DateTime($historial["fecha_inicio"]);
                if ($historial["fecha_fin"] != null) {
                    $fecha2 = new DateTime($historial["fecha_fin"]);
                }else{
                    $fecha2 = new DateTime($hoy);
                }
                $diff = $fecha2->diff($fecha1);
                $ocupados = $diff->format('%a');
                $ocupados_total = $ocupados_total + $ocupados;
            }
            $vacantes = $res["dias"] - $ocupados_total;
        }
        // if($res["RFC"] == null){
        //     $ocupados = 0;
        // }

        // ------------------------------------------------------------------------------------------------------

        $res['usuario'] = $usuario[0];
        $res['puesto'] = $puesto[0];
        $res['departamento'] = $departamento[0];
        $res['vacantes'] = $vacantes;
        $res["ocupados"] = $ocupados_total;
        $arreglo["data"][] = $res;
    }
    echo json_encode($arreglo);
}
mysqli_close($conexion);
