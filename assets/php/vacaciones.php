<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Vacacion WHERE RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);


if (mysqli_num_rows($consulta) == 0) {
    echo '<div class="vacia">
               <i class="material-icons btn2">sms_failed</i>
               <h1>Nada registrado</h1>
					<div class="chat-nuevo">
						<i id="chat-icono" class="material-icons">add</i>
						<p onclick="vacacion(\''.$id.'\');">Asignar dias</p>
					</div>
				</div>';
} else {
    echo '<div class="p-2">
			<h4 class="font-weight-bold text-primary">Lista de vacaciones</h4>
			<small class="text-muted">Vacaciones de '.$usuario["nombre"].'.</small>
		</div>

		<div class="ver_opciones">
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
			<div class="btn btn-secondary btn-sm" onclick="vacacion(\''.$id.'\')"><i class="material-icons">add</i> Nuevo </div>

			</div>
			<div class="card">
				<div class="card-body">
					<div class="caja-vacaciones"></div>
				</div>
			</div>';
}

mysqli_close($conexion);
