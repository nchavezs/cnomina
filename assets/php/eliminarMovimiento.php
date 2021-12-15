<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$condicion = $_POST['condicion'];

$sql = "SELECT RFC FROM Movimiento WHERE id_movimiento = " . $id;
$res = mysqli_query($conexion, $sql);
$usuario = mysqli_fetch_row($res);
$usuario = $usuario[0];
echo $usuario;

if ($condicion == 1) {
    $sql = "SELECT url, puestoAnterior, departamentoAnterior, tipoTrabajadorAnterior FROM Movimiento WHERE id_movimiento = " . $id;
    $consulta = mysqli_query($conexion, $sql);
    $res = mysqli_fetch_row($consulta);
    if ($res[0] != null) {
        $dir = explode("/", $res[0]);
        $url = "./../movimiento/" . $dir[2] . "/*";

        $files = glob($url);
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
    $sql = "UPDATE Usuario SET puesto = '" . $res[1] . "', departamento = '" . $res[2] . "', tipoTrabajador = '".$res[3]."' WHERE RFC = '" . $usuario."'";
    mysqli_query($conexion, $sql);

    $sql = "DELETE FROM Movimiento WHERE id_movimiento = " . $id;
    mysqli_query($conexion, $sql);
}

mysqli_close($conexion);
