<?php
include "conexion.php";
$conexion = conexion();

echo '
<div class="formulario_caja">
	<div class="formulario">
		<form id="form">
			<div class="text-left p-2">
				<h4 class="negrita text-primary">Nuevo rol de usuario</h4>
				<small class="text-muted">Ingresa un nombre de rol y para poder asignarlo a usuarios, despúes selecciona los permisos que se habilitarán para el rol.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="select-etiqueta">Nombre del rol</div>
					<input id="nombre" required type="text" class="campo">

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
								echo '
									<input id="'.$autorizacion[0].'" type="checkbox" value="'.$autorizacion[0].'">
									<label for="'.$autorizacion[0].'">'.$autorizacion["descripcion"].'</label>
								';
						}
					
					echo '
					</div>

				</div>
			</div>
			<div class="pie">
				<div onclick="nuevo_usuario();"class="btn btn-sm btn-secondary">Cancelar</div>
				<button type="submit" class="btn btn-sm btn-success"><i class="material-icons">save</i> Guardar</button>
			</div>
		</form>
	</div>
</div>
';