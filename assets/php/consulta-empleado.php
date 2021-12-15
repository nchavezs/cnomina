<?php
   include("conexion.php");
   $conexion = conexion();

   $sql = "SELECT RFC, nombre, puesto, departamento, estado FROM Usuario WHERE categoria = 'user'";
   $resultado = mysqli_query($conexion, $sql);
	if(mysqli_num_rows($resultado) == 0){
		 echo '{"data":[]}';
	}else{
      while($res = mysqli_fetch_array($resultado)){
         $arreglo["data"][] = $res;
      }
      echo json_encode($arreglo);
   }

   mysqli_close($conexion);
?>
