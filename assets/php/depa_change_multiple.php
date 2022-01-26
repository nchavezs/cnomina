<?php
include "conexion.php";
$conexion = conexion();

if(isset($_POST["departamentos"])){
    $departamentos = implode(",", $_POST["departamentos"]);
    $sql = "SELECT 
    id_puesto, 
    Puesto.nombre AS puesto, 
    Departamento.nombre AS departamento 
    FROM Puesto LEFT JOIN Departamento ON Puesto.id_departamento = Departamento.id_departamento 
    WHERE Puesto.id_departamento IN(" . $departamentos . ")  ORDER BY Puesto.nombre ASC";
    
    $consulta = mysqli_query($conexion, $sql);
    if ($consulta && (mysqli_num_rows($consulta)) > 0) {
        while ($res = mysqli_fetch_array($consulta)) {
            echo '<option data-description="'.$res["departamento"].'" value="' . $res["id_puesto"] . '">' . $res["puesto"] . '</option>';
        }
    }
    mysqli_close($conexion);
    
}