<?php
include "conexion.php";
$conexion = conexion();
$departamento = $_POST["departamento"];

$sql = "SELECT * FROM Puesto WHERE id_departamento = " . $departamento . "  ORDER BY nombre ASC";
$consulta = $conexion->query($sql);
if ($consulta && (mysqli_num_rows($consulta)) > 0) {
    // echo '<option selected value="">SELECCIONAR OPCION</option>';
    while ($res = mysqli_fetch_array($consulta)) {
        echo '<option value="' . $res[0] . '">' . $res[1] . '</option>';
    }
} else {
    echo '<option selected="true" value="">NO HAY OPCIONES DISPONIBLES</option>';
}

$conexion->close();