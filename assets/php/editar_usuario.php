<?php
session_start();
$my_id = $_SESSION["usuario"];
include "conexion.php";
include "rol.php";
$conexion = conexion();
$id = $_POST["id"];

$sql = "SELECT *,
(SELECT id_rol FROM Rol_Usuario WHERE RFC = Usuario.RFC) AS rol 
FROM Usuario WHERE categoria = 'admin' AND RFC = '".$id."'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);



if(in_array(16, rol())){
	$eliminar = "eliminar_usuario('".$id."');";
}else{
	$eliminar = "bloqueo();";
}

if(in_array(45, rol())){
	$baja = "baja_usuario('".$id."');";
}else{
	$baja = "bloqueo();";
}

if(in_array(46, rol())){
	$reset = "password_usuario('".$id."');";
}else{
	$reset = "bloqueo();";
}


if($usuario["estado"] == "alta"){
	$comprobar_baja = '<label onclick="'.$baja.'" class="btn-mostrar"><i class="material-icons">thumb_down</i> Dar de baja</label>';
}else{
	$comprobar_baja = "";
}


if($my_id != $id){
	$comprobar_id = '
	<hr></hr>	
	<div class="card">
		<div class="card-body msn-mostrar">
			<label onclick="'.$eliminar.'" class="btn-mostrar"><i class="material-icons">delete</i> Eliminar usuario</label>
			'.$comprobar_baja.'
			<label onclick="'.$reset.'" class="btn-mostrar"><i class="material-icons">refresh</i> Restablecer contraseña</label>
		</div>	
	</div>
	';
}else{
	$comprobar_id = '';
}


echo '
<div class="formulario_caja">
	<div class="formulario">
		<form id="form">
			<div class="text-left p-2">
				<h4 class="negrita text-primary">Editar usuario</h4>
				<small class="text-muted">Actualiza la información del usuario.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="select-etiqueta">Nombre</div>
					<input id="nombre" required type="text" class="campo" value="'.$usuario["nombre"].'">

					<div class="select-etiqueta">Alias</div>
					<input id="alias" required type="text" class="campo" value="'.$usuario["RFC"].'">

					<div class="select-etiqueta">Email</div>
					<input id="email" required type="email" class="campo" value="'.$usuario["email"].'">

					<div class="select">
						<div class="select-etiqueta">Rol</div>
						<select id="rol">';
						$sql = "SELECT * FROM Rol ORDER BY nombre ASC";
						$consulta = $conexion->query($sql);

						while ($rol = mysqli_fetch_array($consulta)) {
							if($usuario["rol"] == $rol[0]){
								echo '<option selected value="' . $rol[0] . '">' . $rol["nombre"] . '</option>';
							}else{
								echo '<option value="' . $rol[0] . '">' . $rol["nombre"] . '</option>';
							}
						}

						echo '</select>
					</div>
				</div>
			</div>
			'.$comprobar_id.'
			<div class="pie">
				<div id="salir" class="btn btn-sm btn-secondary">Cancelar</div>
				<button type="submit" class="btn btn-sm btn-success"><i class="material-icons">save</i> Actualizar información</button>
			</div>
		</form>
	</div>
</div>
';
