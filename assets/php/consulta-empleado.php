<?php
   include("conexion.php");
   $conexion = conexion();
   $estado = $_POST["estado"];

   $sql = "SELECT RFC, nombre, puesto, departamento, tipoTrabajador,id_usuario FROM Usuario WHERE 
   categoria = 'user' AND 
   estado = '".$estado."'";
   $resultado = mysqli_query($conexion, $sql);
	if(mysqli_num_rows($resultado) == 0){
		 echo '{"data":[]}';
	}else{
      while($res = mysqli_fetch_array($resultado)){
         $res["id_usuario"] = str_pad($res["id_usuario"], 5, '0', STR_PAD_LEFT);
         if($res["tipoTrabajador"] == null)$res["tipoTrabajador"] = "N/A";
         $arreglo["data"][] = $res;
      }
      echo json_encode($arreglo);
   }

   mysqli_close($conexion);
?>
