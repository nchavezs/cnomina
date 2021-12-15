<?php
   include("conexion.php");
   $conexion = conexion();

   $sql = "SELECT id_departamento, nombre FROM Departamento";
   $resultado = mysqli_query($conexion, $sql);
	if($resultado && (mysqli_num_rows($resultado) == 0)){
		 echo '{"data":[]}';
	}else{
		$i = 1;
      while($res = mysqli_fetch_array($resultado)){
			$res['numero'] = $i++;
         $arreglo["data"][] = $res;
      }
      echo json_encode($arreglo);
   }
   mysqli_close($conexion);
?>