<?php
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$hoy = date("d/m/Y");

echo '<form id="form-periodo">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title">Eliminar recibos de nómina</h4>
					<p class="card-category">Indique el periodo que desea eliminar</p>
				</div>
				<div class="card-body">
					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta ">Fecha del</div>
								<input id="fecha_del" type="text" class="form-control datepicker-here" value="' . $hoy . '" readonly/>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta ">Fecha al</div>
								<input id="fecha_al" type="text" class="form-control datepicker-here" value="' . $hoy . '" readonly />
							</div>
						</div>
						
					</div>
				</div>
				<div id="advertencia" class="hide"><i class="material-icons">error</i>Completa todos los campos</div>
			</div>

			<div class="row">
				<div class="col-6">
					<div class="btn btn-secondary btn-sm regresar " onclick="cerrar();"><i class="material-icons">arrow_back</i> Cancelar </div>
				</div>
				<div class="col-6">
					<button type="submit" class="btn btn-secondary btn-sm regresar " ><i class="material-icons">delete</i> Eliminar </button>
				</div>
			</div>
		</form>';