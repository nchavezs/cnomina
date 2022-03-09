<?php
include "../../conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION["usuario"];
$ano = $_POST["ano"];

$sql = "SELECT * FROM Movimiento WHERE YEAR(fecha) = " . $ano . " AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="titulo">Fecha</th>
					<th class="oculto">Puesto actual</th>
					<th class="oculto">Departamento actual</th>
					<th class="titulo">Archivo</th>
					<th class="titulo">Detalle</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_array($consulta)) {
        echo '<tr>
				<td>' . date("d/m/Y", strtotime($res[2])) . '</td>
				<td class="oculto">' . $res["puesto"] . '</td>
				<td class="oculto">' . $res["departamento"] . '</td>
				<td> <a class="material-icons btn1" onclick="archivo(\''.$res["url"].'\')">attachment</a></td>
				<td> <a class="material-icons btn1" onclick="detalle_movimiento('.$res["id_movimiento"].')" >visibility</a></td>
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

$conexion->close();
