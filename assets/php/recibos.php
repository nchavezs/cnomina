<?php
include "conexion.php";
$conexion = conexion();

$sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $_POST['id'] . "'";
$resultado = mysqli_query($conexion, $sql);
$usuario = mysqli_fetch_array($resultado);

echo '<div class="p-2">
		<h4 class="font-weight-bold text-primary">Lista de CFDI</h4>
		<small class="text-muted">CFDI de '.$usuario["nombre"].'.</small>
	</div>

<div class="card">
		<div class="card-body">
			<div class="caja-recibos"></div>
		</div>
	</div>';

mysqli_close($conexion);

// <select id="ano" class="sources">';
// 		$ano = date("Y");
// 		for ($i = 2022; $i <= 2025; $i++) {
// 			if ($ano == $i) {
// 				$select_ano = "selected";
// 			} else {
// 				$select_ano = "";
// 			}
// 			echo '<option ' . $select_ano . ' value="' . $i . '">' . $i . '</option>';
// 		}
// echo '</select>
