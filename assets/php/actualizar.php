<?php
   include("conexion.php");
   $conexion = conexion();
   $sql = "UPDATE Usuario SET contrasenia = '".$_POST['pass']."', estado = 'alta'  WHERE RFC = '".$_POST['id']."'";
   if($conexion->query($sql))
      echo 1;
    else
      echo 0;
   
    $conexion->close();
?>
