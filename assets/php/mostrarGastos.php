<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$ano = $_POST["ano"];
$mes = $_POST["mes"];

$sql = "SELECT * FROM Gastos WHERE YEAR(fecha) = " . $ano . " AND MONTH(fecha) = " . $mes . " AND RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="titulo">ID</th>
					<th class="titulo">Fecha</th>
					<th class="titulo">Monto</th>
					<th class="col-puesto">Archivo</th>
					<th class="titulo">Detalle</th>
					<th class="titulo">Eliminar</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_array($consulta)) {
		echo '<tr>
				<td>' . str_pad($res[0] , 4, '0', STR_PAD_LEFT). '</td>
				<td>' . date("d/m/Y", strtotime($res['fecha'])) . '</td>
				<td>$ ' . $res['monto'] . '</td>
				<td class="col-puesto"> <a class="material-icons btn1" onclick="archivo(' . $res[0] . ',\'' . $res['url'] . '\',\'' . $res[1] . '\',\'Gastos\',0)">attachment</a></td>
				<td> <a class="material-icons btn1" id="' . $res[0] . '-" onclick="detalle_gastos(this.id)" >visibility</a></td>
				<td> <a class="material-icons btn1" id="' . $res[0] . '" onclick="borrar_gastos(this.id)">delete</a></td>
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
