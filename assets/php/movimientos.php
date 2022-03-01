<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id."'";
$consulta = mysqli_query($conexion, $sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Movimiento WHERE id_prenomina = ".$id_prenomina." AND RFC = '" . $id."'";
$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) == 0) {
    echo '<div class="vacia">
               <i class="material-icons btn2">sms_failed</i>
               <h2>Nada registrado</h2>
					<div class="chat-nuevo">
						<i id="chat-icono" class="material-icons">add</i>
						<p onclick="movimiento(\''.$id.'\');">Nuevo movimiento</p>
					</div>
				</div>';
} else {
    echo '<div class="p-2">
				<h4 class="font-weight-bold text-primary">Lista de movimientos</h4>
				<small class="text-muted">Movimientos de '.$usuario["nombre"].'.</small>
			</div>

			<div class="ver_opciones">
				<div class="btn btn-secondary btn-sm" onclick="movimiento(\''.$id.'\')"><i class="material-icons">add</i> Nuevo movimiento</div></div>
			</div>

			<div class="card ver_tabla">
				<div class="card-body">
					<div class="caja-movimientos"></div>
				</div>
			</div>';
}

mysqli_close($conexion);


// <select class="sources" id="ano"' ;
// 						$ano = date("Y");
// 						for($i=2021;$i<=2025;$i++){
// 							if($ano == $i)
// 								$select_ano = "selected";
// 							else
// 								$select_ano = "";
// 							echo '<option '.$select_ano.' value="'.$i.'">'.$i.'</option>';
// 						}
// 				echo '</select>
