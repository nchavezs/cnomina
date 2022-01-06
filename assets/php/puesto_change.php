<?php
include "conexion.php";
$conexion = conexion();
$puesto = $_POST["puesto"];
$departamento = $_POST["departamento"];


$sql = "SELECT nombre FROM Departamento WHERE id_departamento = " . $puesto[1];
$consulta = mysqli_query($conexion, $sql);
$departamento = mysqli_fetch_row($consulta);

$sql = "SELECT * FROM Plaza WHERE id_puesto = " . $id_puesto . " ORDER BY elaboracion";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    while ($res = mysqli_fetch_array($consulta)) {
        echo '<option value="' . $res[0] . '">' . $res[1] . '</option>';
    }
} else {
    echo '<option selected="true" value="">NO HAY OPCIONES DISPONIBLES</option>';
}
