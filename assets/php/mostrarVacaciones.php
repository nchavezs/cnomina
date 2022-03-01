<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
// $ano = $_POST["ano"];

$sql = "SELECT * FROM Vacacion WHERE id_prenomina = ".$id_prenomina." AND RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="titulo">Fecha del</th>
					<th class="titulo">Fecha al</th>
					<th class="col-puesto">Dias</th>
					<th class="col-puesto">Archivo</th>
					<th class="titulo">Detalle</th>
					<th class="titulo">Eliminar</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_row($consulta)) {
        echo '<tr>
				<td>' . date("d/m/Y", strtotime($res[4])) . '</td>
				<td>' . date("d/m/Y", strtotime($res[5])) . '</td>
				<td class="col-puesto">' . $res[3] . '</td>
				<td class="col-puesto"> <a class="material-icons btn1" onclick="archivo(' . $res[0] . ',\'' . $res[7] . '\',\'' . $res[1] . '\',\'Vacacion\',0)">attachment</a></td>
				<td> <a class="material-icons btn1" onclick="detalle_vacacion(\''.$res[0].'\')" >visibility</a></td>
				<td> <a class="material-icons btn1" onclick="borrar_vacacion(\''.$res[0].'\')">delete</a></td>
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
