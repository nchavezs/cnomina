<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];


$sql = "SELECT * FROM Gastos WHERE id_prenomina = ".$id_prenomina." AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="">Fecha</th>
					<th class="">Monto</th>
					<th class="oculto">Archivo</th>
					<th class="">Detalle</th>
					<th class="">Eliminar</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_array($consulta)) {
		echo '<tr>
				<td>' . date("d/m/Y", strtotime($res['fecha'])) . '</td>
				<td>$ ' . $res['monto'] . '</td>
				<td class="oculto"> <a class="material-icons btn1" onclick="archivo(' . $res[0] . ',\'' . $res['url'] . '\',\'' . $res[1] . '\',\'Gastos\',0)">attachment</a></td>
				<td> <a class="material-icons btn1" onclick="detalle_gastos('.$res[0].')" >visibility</a></td>
				<td> <a class="material-icons btn1" onclick="borrar_gastos('.$res[0].')">delete</a></td>
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
