<?php
include "conexion.php";
$conexion = conexion();
$departamento = $_POST["departamento"];
$RFC = $_POST["RFC"];

$sql = "SELECT * FROM Departamento WHERE nombre = '" . $departamento . "'";
$consulta = $conexion->query($sql);
$res = mysqli_fetch_row($consulta);
$id_departamento = $res[0];

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $RFC . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);


if($departamento == $usuario["departamento"]){
    $sql = "SELECT * FROM Puesto WHERE nombre = '" . $usuario["puesto"] . "' AND ocupado = cantidad";
    $consulta = $conexion->query($sql);
    if ($consulta && (mysqli_num_rows($consulta)) > 0) {
        echo '<option value="' . $usuario["puesto"] . '">' . $usuario["puesto"] . ' (0)</option>';
    }

    $sql = "SELECT * FROM Puesto WHERE id_departamento = " . $id_departamento . " AND ocupado < cantidad ORDER BY nombre ASC";
    $consulta = $conexion->query($sql);
    if ($consulta && (mysqli_num_rows($consulta)) > 0) {
        while ($res2 = mysqli_fetch_array($consulta)) {
            $vacantes = $res2["cantidad"] - $res2["ocupado"];
            echo '<option value="' . $res2[1] . '">' . $res2[1] .' ('.$vacantes.')</option>';
        }
    }
}else{
    $sql = "SELECT * FROM Puesto WHERE id_departamento = " . $id_departamento . " AND ocupado < cantidad ORDER BY nombre ASC";
    $consulta = $conexion->query($sql);
    if ($consulta && (mysqli_num_rows($consulta)) > 0) {
        while ($res2 = mysqli_fetch_array($consulta)) {
            $vacantes = $res2["cantidad"] - $res2["ocupado"];
            echo '<option value="' . $res2[1] . '">' . $res2[1] .' ('.$vacantes.')</option>';
        }
    } else {
        echo '<option selected="true" value="">NO HAY OPCIONES DISPONIBLES</option>';
    }
}

