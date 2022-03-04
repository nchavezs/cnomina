<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
// $ano = $_POST["ano"];
$categoria = $_POST["categoria"];

$sql = "SELECT * FROM Permiso WHERE id_prenomina=".$id_prenomina." AND categoria = " . $categoria . " AND RFC = '" . $id . "'";
$resultado = $conexion->query($sql);

if (($total = mysqli_num_rows($resultado)) > 0) {
    echo '<div class="card">
			<div class="card-body">
					<div class="table-responsive">
						<table class="table">
							<thead class=" text-primary">
									<th class="">Fecha del</th>
									<th class="">Fecha al</th>
									<th class="oculto">Dias</th>
									<th class="oculto">Archivo</th>
									<th class="">Detalle</th>
									<th class="">Eliminar</th>
							</thead>
							<tbody>';
    while ($res = mysqli_fetch_array($resultado)) {
        echo '<tr>
				<td>' . date("d/m/Y", strtotime($res["del"])) . '</td>
				<td>' . date("d/m/Y", strtotime($res["al"])) . '</td>
				<td class="oculto">' . $res["dias"] . '</td>
				<td class="oculto"> <a class="material-icons btn1" onclick="archivo(' . $res[0] . ',\'' . $res["url"] . '\',\'' . $res["RFC"] . '\',\'Permiso\',0)">attachment</a></td>
				<td> <a class="material-icons btn1" onclick="detalle(\''.$res[0].'\')" >visibility</a></td>
				<td> <a class="material-icons btn1" onclick="borrar(\''.$res[0].'\')">delete</a></td>
		</tr>';
    }
    echo '</tbody>
			</table>
		</div>
	</div>
</div>';
} else {
    echo '<div class="card">
			<div class="card-body">
					<div class="table-responsive">
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
					</div>
				</div>
			</div>';
}

$conexion->close();
