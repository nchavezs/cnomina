<?php
include "conexion.php";
$conexion = conexion();

echo '<p>No se encontró ningún archivo, seleccione un archivo para continuar.</p>
	</div>
	<div class="text-center">
		<input type="file" accept=".pdf, .xlsx" id="file">
		<label for="file" class="btn-3">
			<span><i class="material-icons">cloud_upload</i>Subir archivo</span>
		</label>
	</div>';