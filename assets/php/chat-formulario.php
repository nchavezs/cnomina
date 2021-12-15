<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_SESSION['usuario'];

$sql1 = "SELECT nombre FROM Usuario WHERE RFC = '" . $id."'";
$resultado1 = mysqli_fetch_row(mysqli_query($conexion, $sql1));

$sql2 = "SELECT nombre FROM Usuario WHERE RFC = 'admin'";
$resultado2 = mysqli_fetch_row(mysqli_query($conexion, $sql2));
$nombre = $resultado2[0];
if ($nombre === '' || is_null($nombre)) {
    $nombre = 'Administrador';
}

echo '<form id="formulario-chat">
			<div class="chat-formulario">
				<div class="row">
					<div class="col-md-12 texto-chat">
						<p> <a class="negrita">De: </a>' . $resultado1[0] . '</p>
					</div>
					<div class="col-md-12 texto-chat">
						<div class="form-group">
							<p> <a class="negrita">Para: </a>' . $nombre . '</p>
						</div>
					</div>
					<div class="col-md-12 texto-chat asunto">
						<p> <a class="negrita">Asunto:</a> <input id="form-titulo" type="text" class="form-control"
								autocomplete="off" required> </p>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<textarea id="form-mensaje" class="form-control" rows="7" autocomplete="off" required></textarea>
						</div>
					</div>
					<div class="col-md-12">
						<input type="file" id="file" /><label for="file" class="btn-3"><span> <i
									class="material-icons">cloud_upload</i> Subir archivo</span></label>
						<input type="input" id="archivo" hidden="true">
					</div>
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-6">
					<div class="btn btn-primary regresar" onclick="cancelar();"><i class="material-icons">arrow_back</i>
						Cancelar </div>
				</div>
				<div class="col-6">
					<button type="submit" class="btn btn-primary regresar"><i class="material-icons">send</i> Enviar </button>
				</div>
			</div>
		</form>';

mysqli_close($conexion);
