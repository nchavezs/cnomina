<?php
setlocale(LC_ALL, "spanish");
$hoy = date("d/m/Y");

echo '<div class="formulario_caja">
	<form class="formulario" id="form-periodo">
			<div class="p-2">
				<h4 class="font-weight-bold text-primary">Eliminar CFDI</h4>
				<small class="text-muted">Indique el periodo en los que se eliminarán los CFDI.</small>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="select-etiqueta ">Fecha del</div>
							<input id="fecha_del" type="text" class="campo" value="' . $hoy . '" readonly/>
						</div>
						<div class="col-md-6">
							<div class="select-etiqueta ">Fecha al</div>
							<input id="fecha_al" type="text" class="campo" value="' . $hoy . '" readonly />
						</div>
					</div>
				</div>
				<div id="advertencia" class="hide"><i class="material-icons">error</i>Completa todos los campos</div>
			</div>
			<div class="pie">
				<div class="btn btn-secondary btn-sm" onclick="cerrar();">Cancelar </div>
				<button type="submit" class="btn btn-danger btn-sm " ><i class="material-icons">delete</i> Eliminar </button>
			</div>
		</form>
	</div> ';