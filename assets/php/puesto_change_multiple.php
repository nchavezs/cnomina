<?php
include "conexion.php";
$conexion = conexion();

if(isset($_POST["puestos"])){
    $puestos = implode(",", $_POST["puestos"]);
    
    $sql = "SELECT 
    Plaza.*,
    Usuario.nombre,
    (SELECT nombre FROM Puesto WHERE id_puesto = Plaza.id_puesto) AS puesto   
    FROM Plaza LEFT JOIN Usuario ON Plaza.RFC = Usuario.RFC WHERE Plaza.id_puesto IN(" . $puestos . ") ORDER BY Plaza.id_plaza";
    $consulta = mysqli_query($conexion, $sql);
    if ($consulta && (mysqli_num_rows($consulta)) > 0) {
        while ($res = mysqli_fetch_array($consulta)) {
            if($res["RFC"] == null){
                echo '<option data-description="PLAZA #'.$res["id_plaza"].' VACANTE" value="' . $res["id_plaza"] . '">'.$res["puesto"].'</option>';
            }else{
                echo '<option data-description="'.$res["nombre"].'" value="' . $res["id_plaza"] . '">'.$res["puesto"].'</option>';
            }
        }
    }
    
    mysqli_close($conexion);
}
