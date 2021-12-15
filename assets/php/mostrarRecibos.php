<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$ano = $_POST["ano"];

$sql = "SELECT * FROM Archivo WHERE RFC = '" . $id . "' AND ano = " . $ano;
$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
						<th>Fecha de pago</th>
						<th>Ver archivo</th>
						<th>Descargar</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_array($resultado)) {
        echo '<tr>
				<td>' . $res[8] . '</td>
				<td> <a class="material-icons btn1" target="_blank" href="' . $res[4] . '">visibility</a></td>
				<td> <a class="material-icons btn1" href="' . $res[4] . '" download>cloud_download</a></td>
			</tr>';
    }
    echo '</tbody>
			</table>
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
