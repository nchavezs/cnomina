<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
include "rol.php";
$conexion = conexion();
$id = $_POST['id'];

$sql1 = "SELECT * FROM Usuario WHERE RFC = '" . $id . "'";
$consulta1 = mysqli_query($conexion, $sql1);
$usuario = mysqli_fetch_array($consulta1);

$sql = "SELECT * FROM Gastos WHERE id_prenomina = ".$id_prenomina." AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

// ----------------------------------------------------
$bloqueo = 'bloqueo()';

if(in_array(11 , rol())){
	$bloqueo = 'gastos(\''.$id.'\')';
}
// ----------------------------------------------------



if (mysqli_num_rows($consulta) == 0) {
    echo '<div class="vacia">
               <i class="material-icons btn2">sms_failed</i>
               <h2>Nada registrado</h2>
					<div onclick="'.$bloqueo.'" class="chat-nuevo">
						<i id="chat-icono" class="material-icons">add</i>
						<p>Nuevo gasto médico</p>
					</div>
				</div>';
} else {
    echo '
	<div class="p-2">
        <h4 class="negrita text-primary">Lista de gastos médicos</h4>
        <small class="text-muted">Gastos médicos de '.$usuario["nombre"].'.</small>
    </div>
	<div class="ver_opciones">
			<div class="btn btn-secondary btn-sm" onclick="'.$bloqueo.'"><i class="material-icons">add</i> Nuevo </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="caja-gastos"></div>
		</div>
	</div>';
}

$conexion->close();
