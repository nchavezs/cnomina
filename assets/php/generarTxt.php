<?php
   include("conexion.php");
   $conexion = conexion();
	$categoria = $_POST["categoria"];
   setlocale(LC_ALL, "spanish");
	$hoy = date('d_m_Y_H_i_s');
	

   $sql = "SELECT nombre FROM ".$categoria;
   $resultado = mysqli_query($conexion, $sql);
	if(mysqli_num_rows($resultado) == 0){
		echo 0;
	}else{
      while($res = mysqli_fetch_array($resultado)){
			file_put_contents("../archivos/archivo_".$hoy.".txt",$res[0].PHP_EOL, FILE_APPEND);
      }
      echo "assets/archivos/archivo_".$hoy.".txt";
   }
   mysqli_close($conexion);
?>