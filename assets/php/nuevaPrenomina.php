<?php
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
include "conexion.php";
$conexion = conexion();

$hoy = date("d/m/Y");

echo '<div class="formulario row">
		<div class="col-md-4 p-0">
			<div class="wallpaper">
				<img src="assets/img/form.svg" alt="">
			</div>
		</div>

		<div class="col-md-8 p-0">
			<div class="card-body">
				<form id="form-prenomina">
					<div class="text-left p-2">
						<h4 class="font-weight-bold text-primary">Genera una nueva prenómina</h4>
						<small class="text-muted">Selecciona el periodo y fechas para generar la prenomina.</small>
					</div>
					<div class="card">
						<div class="row card-body">
							<div class="col-md-12">
								<div class="select">
									<div class="select-etiqueta">Periodo</div>
									<select id="periodo" class="">';
									$sql = "SELECT * FROM Periodo WHERE id_periodo <> 3";
									$consulta = mysqli_query($conexion,$sql);
									while($res = mysqli_fetch_array($consulta)){
										echo '<option value="'.$res["id_periodo"].'">'.$res["nombre"].'</option>';
									}
									echo '</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta">Del</div>
									<input id="del" type="text" readonly class="form-control" value="" required/>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<div class="select-etiqueta ">Al</div>
									<input id="al" type="text" disabled readonly class="form-control" value="" required/> 
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<div class="select-etiqueta">Observaciones <cite class="text-danger">opcional</cite></div>
									<textarea id="observacion" class="form-control"></textarea>
								</div>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="card-body text-left">
							<div class="row">
								<div class="col-2">
									<div class="toggle-btn active">
										<input id="check1" type="checkbox" class="cb-value" checked />
										<span class="round-btn"></span>
									</div>
								</div>
								<div class="col-10">
									<p class="text-muted">¿Actualizar fechas a partir de la última prenómina registrada?</p>
								</div>
							</div>
						</div>
					</div>

					<div class="text-right p-3 pagina_2_opciones">
						<div id="salir" class="btn btn-secondary btn-sm">Cancelar </div>
						<button type="submit" class="btn btn-success btn-sm"><i class="material-icons">save</i> Generar prenomina</button>
					</div>
				</form>
			</div>
		</div>
	</div>';

mysqli_close($conexion);