<?php
include "conexion.php";
$conexion = conexion();
$puesto = $_POST["puesto"];
$puestos = implode(",", $puesto);

$sql = "SELECT * FROM Puesto WHERE id_puesto IN(" . $puesto . ")  ORDER BY nombre ASC";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    while ($res = mysqli_fetch_array($consulta)) {
        echo '<option value="' . $res[0] . '">' . $res[1] . '</option>';
    }
}
mysqli_close($conexion);