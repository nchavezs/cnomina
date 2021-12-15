<?php
   include("conexion.php");
   $conexion = conexion();
   $sql = "UPDATE Usuario SET contrasenia = '".$_POST['pass']."', estado = 'alta'  WHERE RFC = '".$_POST['id']."'";
   if(mysqli_query($conexion, $sql))
      echo 1;
    else
      echo 0;
   
    mysqli_close($conexion);
?>
