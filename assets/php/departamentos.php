<?php
	include("conexion.php");
	$conexion =  conexion();

	echo '<div class="select">
				<div class="select-etiqueta label-depa">Departamento</div>
				<select id="departamento" class="custom-select select-empleado departamento-select">';
	
	$sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
	$consulta = $conexion->query($sql);
	if($consulta && (mysqli_num_rows($consulta)) > 0){
		while($res = mysqli_fetch_row($consulta)){
			echo '<option value="'.$res[1].'">'.$res[1].'</option>';
		}	
	}else{
		echo '<option selected="true" value="">NO HAY OPCIONES DISPONIBLES</option>';
	}
		
	echo '</select>
				</div>';

	$conexion->close();
?>