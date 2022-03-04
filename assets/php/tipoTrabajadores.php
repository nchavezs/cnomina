<?php
	include("conexion.php");
	$conexion =  conexion();

	echo '<div class="select">
				<div class="select-etiqueta label-tipo">Tipo de trabajador</div>
				<select id="trabajador" class="custom-select select-empleado trabajador-select">';
	
	$sql = "SELECT * FROM Trabajador ORDER BY nombre ASC";
	$consulta = $conexion->query($sql);
	if($consulta && (mysqli_num_rows($consulta)) > 0){
		while($res = mysqli_fetch_row($consulta)){
			echo '<option value="'.$res[1].'">'.$res[1].'</option>';
		}	
	}else{
		echo '<option selected="true" value="">NO SE ENCONTRO CATEGORIA</option>';
	}
		
	echo '</select>
				</div>';

	$conexion->close();
?>