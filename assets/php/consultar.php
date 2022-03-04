<?php
   include("conexion.php");
   $conexion = conexion();
   setlocale(LC_ALL, "spanish");
   $ano = $_POST["ano"];
   $id_periodo = $_POST["id_periodo"];
   
   $sql = "SELECT * FROM Archivo WHERE 
   YEAR(del) = ".$ano." AND 
   id_periodo = ".$id_periodo." 
   ORDER BY del DESC";
   $resultado = $conexion->query($sql);
	if(mysqli_num_rows($resultado) == 0){
		 echo '{"data":[]}';
	}else{
      while($res = mysqli_fetch_assoc($resultado)){
         $res["del"] = strftime("%d %B", strtotime($res["del"]));
         $res["al"] = strftime("%d %B", strtotime($res["al"]));
         $arreglo["data"][] = $res;
      }
      echo json_encode($arreglo);
   }

   $conexion->close();
