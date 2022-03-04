<?php
   include 'conexion.php';
   $conexion = conexion();
	$texto = $_POST["texto"];
	$categoria = $_POST["categoria"];

	$sql = "SELECT nombre FROM ".$categoria ." WHERE nombre LIKE '%".$texto."%'";
	
	if(($consulta = $conexion->query($sql)) && trim($texto) != "" && mysqli_num_rows($consulta) > 0){
		echo '<div class="barra-filtro">';
		while($resultado = mysqli_fetch_array($consulta)){
			echo '<div id="'.$resultado[0].'" class="filtro-caja">
						<p>'.$resultado[0].'</p>
					</div>';
		}
		echo '</div>';
	}else{
		echo 0;
	}

	$conexion->close();
?>