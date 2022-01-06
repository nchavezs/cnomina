<?php
include "conexion.php";
$conexion = conexion();

$sql = "SELECT * FROM Plaza";
$resultado = mysqli_query($conexion, $sql);

if ($resultado && (mysqli_num_rows($resultado) == 0)) {
    echo '{"data":[]}';
} else {
    $i = 1;
    while ($res = mysqli_fetch_array($resultado)) {
        // ------------------------------------------------------------------------------------------------------
        $sql = "SELECT nombre FROM Usuario WHERE RFC = '".$res["RFC"]."'";
        $consulta = mysqli_query($conexion, $sql);
        if($consulta && mysqli_num_rows($consulta) > 0){
            $usuario = mysqli_fetch_row($consulta);
        }else{
            $usuario[0] = "SIN ASIGNAR";
        }

        $sql = "SELECT nombre,id_departamento FROM Puesto WHERE id_puesto = ".$res["id_puesto"];
        $consulta = mysqli_query($conexion, $sql);
        $puesto = mysqli_fetch_row($consulta);

        $sql = "SELECT nombre FROM Departamento WHERE id_departamento = ".$puesto[1];
        $consulta = mysqli_query($conexion, $sql);
        $departamento = mysqli_fetch_row($consulta);

        $ocupados = 0;
        $vacantes = $res["dias"];
        // ------------------------------------------------------------------------------------------------------

        $res['numero'] = $i++;
        $res['usuario'] = $usuario[0];
        $res['puesto'] = $puesto[0];
        $res['departamento'] = $departamento[0];
        $res['vacantes'] = $vacantes;
        $res["ocupados"] = $ocupados;
        $arreglo["data"][] = $res;
    }   
    echo json_encode($arreglo);
}
mysqli_close($conexion);