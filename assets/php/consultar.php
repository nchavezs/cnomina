<?php
   include("conexion.php");
   $conexion = conexion();

   $sql = "SELECT * FROM Archivo";
   $resultado = mysqli_query($conexion, $sql);
	if(mysqli_num_rows($resultado) == 0){
		 echo '{"data":[]}';
	}else{
      while($res = mysqli_fetch_assoc($resultado)){
         $res["del"] = date("d/m/Y", strtotime($res["del"]));
         $res["al"] = date("d/m/Y", strtotime($res["al"]));
         $arreglo["data"][] = $res;
      }
      echo json_encode($arreglo);
   }

   mysqli_close($conexion);
