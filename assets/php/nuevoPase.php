<?php
$id = $_POST['id'];
include "conexion.php";
$conexion = conexion();

setlocale(LC_ALL, "spanish");
$hoy = date("d/m/Y");

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id. "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

echo '<form id="form-pase">
			<div class="p-2">
				<h4 class="font-weight-bold text-primary">Registrar nuevo pase</h4>
				<small class="text-muted">Completa el siguiente formulario para realizar un nuevo pase para '.$usuario["nombre"].'.</small>
			</div>
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-6 fecha-date">
								<div id="fecha" class="datepicker-here"></div>
								<input id="pase" type="hidden" />
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12">
										<div class="select">
											<div class="select-etiqueta">Tipo de pase</div>
											<select id="categoria">
												<option value="0">PASE DE ENTRADA</option>
												<option value="1">PASE DE SALIDA</option>
											</select>
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<div class="select-etiqueta">Observación <cite class="text-danger"> opcional</cite></div>
											<textarea id="observacion" class="campo" rows="5"></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div id="advertencia" class="hide"><i class="material-icons">error</i>Selecciona una hora y fecha</div>
				</div>';

echo '<div class="pie">
		<div class="btn btn-secondary btn-sm" onclick="verPases(\''.$id.'\');">Regresar </div>
		<button type="submit" class="btn btn-success btn-sm"><i class="material-icons">save</i> Guardar </button>
	</div>
</form>';

mysqli_close($conexion);
