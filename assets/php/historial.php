<?php
session_start();
include "rol.php";
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);


$baja = 'bloqueo()';
$reingreso = 'bloqueo()';

if(in_array(2 , rol())){
	$baja = 'baja(\''.$id.'\')';
	$reingreso = 'reingreso(\''.$id.'\')';
}

echo '<div class="p-2">
		<h4 class="negrita text-primary">Historial de altas y bajas</h4>
		<small class="text-muted">Altas y bajas de '.$usuario["nombre"].'.</small>
	</div>

	<div class="ver_opciones">';
	if($usuario["estado"] == "alta"){
		echo '<div class="btn btn-secondary btn-sm" onclick="'.$baja.'"><i class="material-icons">add</i> Dar de baja </div>';
	}else{
		echo '<div class="btn btn-secondary btn-sm" onclick="'.$reingreso.'"><i class="material-icons">add</i> Reingreso</div>';
	}
	echo '</div>
		<div class="card">
			<div class="card-body">
				<div class="caja-historial"></div>
			</div>
		</div>';

$conexion->close();



// <select id="ano" class="sources">';
// 			$ano = date("Y");
// 			for($i=2022;$i<=2025;$i++){
// 				$select_ano = "";
// 				if($ano == $i)
// 					$select_ano = "selected";
// 				echo '<option '.$select_ano.' value="'.$i.'">'.$i.'</option>';
// 			}
// 			echo '
// 		</select>