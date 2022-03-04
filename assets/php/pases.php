<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Pase WHERE id_prenomina =".$id_prenomina." AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

// $mes = date("m");
// $mes1 = 1;
// $mes2 = 2;
// $mes3 = 3;
// $mes4 = 4;
// $mes5 = 5;
// $mes6 = 6;
// $mes7 = 7;
// $mes8 = 8;
// $mes9 = 9;
// $mes10 = 10;
// $mes11 = 11;
// $mes12 = 12;

// if ($mes1 == $mes) {
//     $mes1 = 'selected';
// } else {
//     $mes1 = '';
// }

// if ($mes2 == $mes) {
//     $mes2 = 'selected';
// } else {
//     $mes2 = '';
// }

// if ($mes3 == $mes) {
//     $mes3 = 'selected';
// } else {
//     $mes3 = '';
// }

// if ($mes4 == $mes) {
//     $mes4 = 'selected';
// } else {
//     $mes4 = '';
// }

// if ($mes5 == $mes) {
//     $mes5 = 'selected';
// } else {
//     $mes5 = '';
// }

// if ($mes6 == $mes) {
//     $mes6 = 'selected';
// } else {
//     $mes6 = '';
// }

// if ($mes7 == $mes) {
//     $mes7 = 'selected';
// } else {
//     $mes7 = '';
// }

// if ($mes8 == $mes) {
//     $mes8 = 'selected';
// } else {
//     $mes8 = '';
// }

// if ($mes9 == $mes) {
//     $mes9 = 'selected';
// } else {
//     $mes9 = '';
// }

// if ($mes10 == $mes) {
//     $mes10 = 'selected';
// } else {
//     $mes10 = '';
// }

// if ($mes11 == $mes) {
//     $mes11 = 'selected';
// } else {
//     $mes11 = '';
// }

// if ($mes12 == $mes) {
//     $mes12 = 'selected';
// } else {
//     $mes12 = '';
// }

if (mysqli_num_rows($consulta) == 0) {
    echo '<div class="vacia">
			<i class="material-icons btn2">sms_failed</i>
			<h2>Nada registrado</h2>
			<div class="chat-nuevo">
				<i id="chat-icono" class="material-icons">add</i>
				<p onclick="pase(\''.$usuario["RFC"].'\');">Nuevo pase</p>
			</div>
		</div>';
} else {
	echo '<div class="p-2">
			<h4 class="negrita text-primary">Lista de pases</h4>
			<small class="text-muted">Pases de entrada y salida de '.$usuario["nombre"].'.</small>
		</div>
		<div class="ver_opciones">
			<select id="categoria" class="sources">
				<option value="0" selected>Entrada</option>
				<option value="1">Salida</option>
			</select>
			<div class="btn btn-secondary btn-sm" onclick="pase(\''.$usuario["RFC"].'\')"><i class="material-icons">add</i> Nuevo pase</div>
		</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="caja-pases"></div>
			</div>
		</div>';
}

$conexion->close();


// <select id="ano" class="sources">';
// 			$ano = date("Y");
// 			for ($i = 2022; $i <= 2025; $i++) {
// 				$select_ano = "";
// 				if ($ano == $i) {
// 					$select_ano = "selected";
// 				}
// 				echo '<option ' . $select_ano . ' value="' . $i . '">' . $i . '</option>';
// 			}
// 			echo '</select>
// 						<select id="mes" class="sources">
// 							<option ' . $mes1 . ' value="1">Enero</option>
// 							<option ' . $mes2 . ' value="2">Febrero</option>
// 							<option ' . $mes3 . ' value="3">Marzo</option>
// 							<option ' . $mes4 . ' value="4">Abril</option>
// 							<option ' . $mes5 . ' value="5">Mayo</option>
// 							<option ' . $mes6 . ' value="6">Junio</option>
// 							<option ' . $mes7 . ' value="7">Julio</option>
// 							<option ' . $mes8 . ' value="8">Agosto</option>
// 							<option ' . $mes9 . ' value="9">Septiembre</option>
// 							<option ' . $mes10 . ' value="10">Octubre</option>
// 							<option ' . $mes11 . ' value="11">Noviembre</option>
// 							<option ' . $mes12 . ' value="12">Diciembre</option>
// 						</select>