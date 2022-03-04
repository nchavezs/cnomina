<?php
	include("conexion.php");
    $conexion = conexion();
    $id = $_POST['id'];

	$sql = "SELECT * FROM Movimiento WHERE id_movimiento = ".$id;
	$consulta = $conexion->query($sql);
	$movimiento = mysqli_fetch_array($consulta);

	if(trim($movimiento["observacion"]) === "")
		$obs = "Sin observación";
	else
		$obs = $movimiento["observacion"];


	$sql = "SELECT nombre FROM Usuario WHERE RFC = '".$movimiento["RFC"]."'";
	$consulta = $conexion->query($sql);
	$usuario = mysqli_fetch_array($consulta);
	$fecha = date("d/m/Y", strtotime($movimiento["fecha"]));
			
	echo '<div class="p-2">
	<h4 class="negrita text-primary">Detalle de movimiento</h4>
	<small class="text-muted">Detalle de movimiento de '.$usuario["nombre"].'.</small>
	</div>
	<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="select-etiqueta">Fecha de movimiento</div>
						<input id="fecha1" type="text" class="campo datepicker-here" readonly value="'.$fecha.'" />
					</div>

					<div class="col-md-6">
						<div class="select-etiqueta">Puesto anterior</div>
						<input type="text" class="campo" readonly value="'.$movimiento["puestoAnterior"].'" />
					</div>
					<div class="col-md-6">
						<div class="select-etiqueta">Puesto actual</div>
						<input type="text" class="campo" readonly value="'.$movimiento["puesto"].'" />
					</div>

					<div class="col-md-6">
						<div class="select-etiqueta">Departamento anterior</div>
						<input type="text" class="campo" readonly value="'.$movimiento["departamentoAnterior"].'" />
					</div>
					<div class="col-md-6">
						<div class="select-etiqueta">Departamento actual</div>
						<input type="text" class="campo" readonly value="'.$movimiento["departamento"].'" />
					</div>
					<div class="col-md-12">
						<div class="select-etiqueta">Observaciones</div>
						<textarea id="observacion" readonly class="campo" rows="3">'.$obs.'</textarea>
					</div>
				</div>
			</div>
		</div>
		
		<div class="card">
			<div class="card-body centrado">';

			if(!is_null($movimiento["url"])){
				echo '<div class="btn btn-primary btn-sm btn3" onclick="archivo('.$movimiento[0].',\''.$movimiento["url"].'\',\''.$movimiento[1].'\',\'Movimiento\',1)"><i class="material-icons">play_for_work</i> Descargar archivo </div>
					<div class="btn btn-primary btn-sm btn3" onclick="eliminar_archivo('.$movimiento[0].',\'Movimiento\')"><i class="material-icons">clear</i> Eliminar archivo </div>';
			}
			else{
				echo '<div class="btn btn-primary btn-sm btn3" onclick="archivo('.$movimiento[0].',\''.$movimiento["url"].'\',\''.$movimiento[1].'\',\'Movimiento\',1)"><i class="material-icons">cloud_upload</i> Subir archivo </div>';
			}
			
		echo'</div>
		</div>

		<div class="pie">
			<div class="btn btn-secondary btn-sm" onclick="verMovimientos(\''.$movimiento["RFC"].'\');"><i class="material-icons">arrow_back</i> Regresar </div>
		</div>';

	$conexion->close();
?>
