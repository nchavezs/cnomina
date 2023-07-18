<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
include "rol.php";
$conexion = conexion();
$id = $_POST['id'];


$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Vacacion WHERE id_prenomina = ".$id_prenomina." AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

// ----------------------------------------------------
$bloqueo = 'bloqueo()';

if(in_array(11 , rol())){
	$bloqueo = 'vacacion(\''.$id.'\')';
}
// ----------------------------------------------------


if (mysqli_num_rows($consulta) == 0) {
    echo '<div class="vacia">
               <i class="material-icons btn2">sms_failed</i>
               <h2>Nada registrado</h2>
					<div onclick="'.$bloqueo.'" class="chat-nuevo">
						<i id="chat-icono" class="material-icons">add</i>
						<p>Asignar dias</p>
					</div>
				</div>';
} else {
    echo '<div class="p-2">
			<h4 class="negrita text-primary">Lista de vacaciones</h4>
			<small class="text-muted">Vacaciones de '.$usuario["nombre"].'.</small>
		</div>

		<div class="ver_opciones">
			<div class="btn btn-secondary btn-sm" onclick="'.$bloqueo.'"><i class="material-icons">add</i> Nuevo </div>

			</div>
			<div class="card">
				<div class="card-body">
					<div class="caja-vacaciones"></div>
				</div>
			</div>';
}

$conexion->close();

// <select id="ano" class="sources">';
// 			$ano = date("Y");
// 			for($i=2022;$i<=2025;$i++){
// 				$select_ano = "";
// 				if($ano == $i)
// 					$select_ano = "selected";
// 				echo '<option '.$select_ano.' value="'.$i.'">'.$i.'</option>';
// 			}
// 			echo '</select>
