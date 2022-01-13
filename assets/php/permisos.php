<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Permiso WHERE RFC = '" . $id."'";
$resultado = mysqli_query($conexion, $sql);

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

if (mysqli_num_rows($resultado) == 0) {
    echo '<div class="vacia">
               <i class="material-icons btn2">sms_failed</i>
               <h1>Nada registrado</h1>
					<div class="chat-nuevo">
						<i id="chat-icono" class="material-icons">add</i>
						<p id="' . $id . '-" onclick="permiso(this.id);">Nueva licencia</p>
					</div>
				</div>';

    echo '<div class="btn btn-secondary btn-sm regresar " id="' . $id . '" onclick="ver(this.id, 1);"><i class="material-icons">arrow_back</i> Regresar </div>';
} else {
    echo '<div class="row">
					<div class="col-md-3">
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
					<div class="col-md-9">
						<select name="sources" id="permiso" class="select-permiso custom-select sources" >
							<option value="0" selected="true">Permiso con goce de sueldo</option>
							<option value="1">Permiso sin goce de sueldo</option>
						</select>
					</div>
				</div>

				<div id="caja-permiso"></div>';

    echo '<div class="row">
				<div class="col-6">
					<div class="btn btn-secondary btn-sm regresar " id="' . $id . '" onclick="ver(this.id, 1);"><i class="material-icons">arrow_back</i> Regresar </div>
				</div>
				<div class="col-6">
					<div class="btn btn-secondary btn-sm regresar " id="' . $id . '-" onclick="permiso(this.id);"><i class="material-icons">add</i> Nuevo </div>
				</div>
			</div>';
}

mysqli_close($conexion);
