<?php
include "conexion.php";
$conexion = conexion();
$departamento = $_POST["departamento"];

$sql = "SELECT * FROM Departamento WHERE nombre = '" . $departamento . "'";
$consulta = mysqli_query($conexion, $sql);
$res = mysqli_fetch_row($consulta);
$id_departamento = $res[0];


$sql = "SELECT * FROM Puesto WHERE id_departamento = " . $id_departamento . " AND ocupado < cantidad ORDER BY nombre ASC";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    while ($res2 = mysqli_fetch_array($consulta)) {
        $vacantes = $res2["cantidad"] - $res2["ocupado"];
            echo '<option value="' . $res2[1] . '">' . $res2[1] .' ('.$vacantes.')</option>';
    }
} else {
    echo '<option selected="true" value="">NO SE ENCONTRO PUESTO</option>';
}
