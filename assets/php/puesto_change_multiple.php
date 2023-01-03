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
    $consulta = $conexion->query($sql);
    if ($consulta && (mysqli_num_rows($consulta)) > 0) {
        while ($res = mysqli_fetch_array($consulta)) {

            $ano = date("Y", strtotime($res["elaboracion"]));
            if($res["RFC"] == null){
                echo '<option data-description="PLAZA #'.$res["id_plaza"].' POR EJERCER" value="' . $res["id_plaza"] . '">'.$ano." ➟ ".$res["puesto"].'</option>';
            }else{
                echo '<option data-description="'.$res["nombre"].'" value="' . $res["id_plaza"] . '">'.$ano." ➟ ".$res["puesto"].'</option>';
            }
        }
    }
    
    $conexion->close();
}
