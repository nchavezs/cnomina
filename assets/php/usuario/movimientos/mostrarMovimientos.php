<?php
include "../../conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION["usuario"];
$ano = $_POST["ano"];

$sql = "SELECT * FROM Movimiento WHERE YEAR(fecha) = " . $ano . " AND RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="titulo">Fecha cambio</th>
					<th class="col-puesto">Puesto actual</th>
					<th class="col-puesto">Departamento actual</th>
					<th class="titulo">Archivo</th>
					<th class="titulo">Detalle</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_row($consulta)) {
        echo '<tr>
				<td>' . date("d/m/Y", strtotime($res[2])) . '</td>
				<td class="col-puesto">' . $res[3] . '</td>
				<td class="col-puesto">' . $res[4] . '</td>
				<td> <a class="material-icons btn1" id="' . $res[5] . '" onclick="archivo(this.id)">attachment</a></td>
				<td> <a class="material-icons btn1" id="' . $res[0] . '" onclick="detalle_movimiento(this.id)" >visibility</a></td>
			</tr>';
    }
    echo '</tbody>
			</table>
		</div>
	</div>
</div>';
} else {
    echo '<div class="table-responsive">
		<table class="table">
			<thead class=" text-primary">
			</thead>
			<tbody>
				<div class="chat-nuevo">
					<i id="chat-icono" class="material-icons">error_outline</i>
					<p>Sin elementos</p>
				</div>
			</tbody>
		</table>
	</div>';
}

mysqli_close($conexion);
