<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Movimiento WHERE RFC = '" . $id."'";
$consulta = mysqli_query($conexion, $sql);

$sql1 = "SELECT * FROM Usuario WHERE RFC = '" . $id."'";
$consulta1 = mysqli_query($conexion, $sql1);
$resultado1 = mysqli_fetch_array($consulta1);
$nombre = $resultado1[6];

// $ano = date("Y");
// $ano1 = 2018;
// $ano2 = 2019;
// $ano3 = 2020;
// $ano4 = 2021;

// if ($ano1 == $ano) {
//     $ano1 = 'selected="true"';
// } else {
//     $ano1 = '';
// }

// if ($ano2 == $ano) {
//     $ano2 = 'selected="true"';
// } else {
//     $ano2 = '';
// }

// if ($ano3 == $ano) {
//     $ano3 = 'selected="true"';
// } else {
//     $ano3 = '';
// }

// if ($ano4 == $ano) {
//     $ano4 = 'selected="true"';
// } else {
//     $ano4 = '';
// }

if (mysqli_num_rows($consulta) == 0) {
    echo '<div class="vacia">
               <i class="material-icons btn2">sms_failed</i>
               <h1>Nada registrado</h1>
					<div class="chat-nuevo">
						<i id="chat-icono" class="material-icons">add</i>
						<p onclick="movimiento(\''.$id.'\');">Nuevo movimiento</p>
					</div>
				</div>';
    echo '<div class="btn btn-primary regresar" onclick="ver(\''.$id.'\', 1);"><i class="material-icons">arrow_back</i> Regresar </div>';
} else {
    echo '<div class="row">
					<div class="col-3">
					<select name="sources" id="ano" class="custom-select sources">';
					$ano = date("Y");
					for($i=2018;$i<=2023;$i++){
						if($ano == $i)
							$select_ano = "selected";
						else
							$select_ano = "";
						echo '<option '.$select_ano.' value="'.$i.'">'.$i.'</option>';
					}
					echo '</select>
					</div>
				</div>
				<div class="card card-profile ">
					<div class="card-header card-header-primary">
						<h4 class="card-title ">Movimientos</h4>
						<p id="total" class="card-category">Empleado: ' . $nombre . '</p>
					</div>
					<div class="card-body">
						<div class="caja-movimientos"></div>
					</div>
				</div>';

    echo '<div class="row">
					<div class="col-6">
						<div class="btn btn-primary regresar" onclick="ver(\''.$id.'\', 1)"><i class="material-icons">arrow_back</i> Regresar </div>
						</div>
					<div class="col-6">
						<div class="btn btn-primary regresar" onclick="movimiento(\''.$id.'\')"><i class="material-icons">add</i> Nuevo </div>
						</div>
					</div>
				</div>';
}

mysqli_close($conexion);
