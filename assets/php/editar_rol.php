<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST["id"];

$sql = "SELECT * FROM Rol WHERE id_rol = ".$id;
$consulta = $conexion->query($sql);
$rol = mysqli_fetch_array($consulta);

$autorizaciones=[];

$sql = "SELECT id_autorizacion FROM Rol_Autorizacion WHERE id_rol = ".$id;
$consulta = $conexion->query($sql);
while($autorizacion = mysqli_fetch_row($consulta)){
	$autorizaciones[] = $autorizacion[0];
}

echo '
<div class="formulario_caja">
	<div class="formulario">
		<form id="form">
			<div class="text-left p-2">
				<h4 class="negrita text-primary">Editar rol de usuario</h4>
				<small class="text-muted">Asigna un nombre de rol y selecciona los permisos correspondientes.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="select-etiqueta">Nombre del rol</div>
					<input id="nombre" required type="text" class="campo" value="'.$rol["nombre"].'">

					<div class="checklist">';
						$sql = "SELECT *, 
							(SELECT nombre FROM Categoria WHERE id_categoria = Autorizacion.id_categoria) AS categoria 
							FROM Autorizacion ORDER BY id_categoria, id_autorizacion";
							$consulta = $conexion->query($sql);
							$categoria = 0;
							while ($autorizacion = mysqli_fetch_array($consulta)) {
								if($categoria != $autorizacion["id_categoria"]){
									echo '
										<span class="material-icons">adjust</span>
										<span class="negrita my-3">'.mb_strtoupper($autorizacion["categoria"]).'</span>
									';
									$categoria = $autorizacion["id_categoria"];
								}

								if(in_array($autorizacion["id_autorizacion"], $autorizaciones)){
									$checked = "checked";
								}else{
									$checked = "";
								}

								echo '
									<input id="'.$autorizacion[0].'" type="checkbox" value="'.$autorizacion[0].'" '.$checked.'>
									<label for="'.$autorizacion[0].'">'.$autorizacion["descripcion"].'</label>
								';
						}
					
					echo '
					</div>

				</div>
			</div>
			<div class="pie">
				<div onclick="editar_rol_select();"class="btn btn-sm btn-secondary"><i class="material-icons">keyboard_backspace</i> Regresar</div>
				<button type="submit" class="btn btn-sm btn-success"><i class="material-icons">save</i> Actualizar datos</button>
			</div>
		</form>
	</div>
</div>
';