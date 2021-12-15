<?php
include "conexion.php";
$conexion = conexion();

$sql = "SELECT id_puesto, Puesto.nombre, Departamento.nombre AS 'departamento', Puesto.cantidad, Puesto.ocupado FROM Puesto JOIN Departamento ON Puesto.id_departamento = Departamento.id_departamento";
$resultado = mysqli_query($conexion, $sql);
if ($resultado && (mysqli_num_rows($resultado) == 0)) {
    echo '{"data":[]}';
} else {
    $i = 1;
    while ($res = mysqli_fetch_array($resultado)) {
        $res['numero'] = $i++;
        $res['disponible'] = $res['cantidad'] - $res['ocupado'];
        $arreglo["data"][] = $res;
    }   
    echo json_encode($arreglo);
}
mysqli_close($conexion);