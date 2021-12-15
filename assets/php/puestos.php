<?php
	include("conexion.php");
	$conexion =  conexion();

	echo '<div class="select">
				<div class="select-label label-puesto">Puesto</div>
				<select id="puesto" class="custom-select select-empleado puesto-select">';
	
	$sql = "SELECT * FROM Puesto ORDER BY nombre ASC";
	$consulta = mysqli_query($conexion, $sql);
	if($consulta && (mysqli_num_rows($consulta)) > 0){
		while($res = mysqli_fetch_row($consulta)){
			echo '<option value="'.$res[1].'">'.$res[1].'</option>';
		}	
	}else{
		echo '<option selected="true" value="">NO SE ENCONTRO PUESTO</option>';
	}
		
	echo '</select>
				</div>';

	mysqli_close($conexion);
?>