<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Usuario WHERE RFC = '".$id."'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Permiso WHERE RFC = '" . $id."'";
$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) == 0) {
    echo '<div class="vacia">
			<i class="material-icons btn2">sms_failed</i>
			<h2>Nada registrado</h2>
			<div class="chat-nuevo">
				<i id="chat-icono" class="material-icons">add</i>
				<p onclick="permiso(\''.$id.'\');">Nueva licencia</p>
			</div>
		</div>';
} else {
    echo '<div class="p-2">
			<h4 class="font-weight-bold text-primary">Lista de licencias</h4>
			<small class="text-muted">Permisos con goce de sueldo, sin goce de sueldo de '.$usuario["nombre"].'.</small>
		</div>
		<div class="ver_opciones">
			<select name="sources" id="permiso" class="select-permiso custom-select sources" >
				<option value="0" selected="true">CON GOCE DE SUELDO</option>
				<option value="1">SIN GOCE DE SUELDO</option>
			</select>
			<select id="ano" class="sources">';
			$ano = date("Y");
			for($i=2022;$i<=2025;$i++){
				$select_ano = "";
				if($ano == $i)
					$select_ano = "selected";
				echo '<option '.$select_ano.' value="'.$i.'">'.$i.'</option>';
			}
			echo '
			</select>
			<div class="btn btn-secondary btn-sm" onclick="permiso(\''.$id.'\');"><i class="material-icons">add</i> Nuevo </div>
	</div>
	
	<div id="caja-permiso"></div>';
}

mysqli_close($conexion);
