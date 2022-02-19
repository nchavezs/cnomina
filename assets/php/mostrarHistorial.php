<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
// $ano = $_POST["ano"];

$sql = "SELECT * FROM Historial WHERE RFC = '".$id."' ORDER BY elaboracion DESC";

$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="">Tipo</th>
					<th class="">Fecha</th>
					<th class="oculto">Descripción</th>
					<th class="">Formato</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_array($consulta)) {
		if($res["descripcion"] == null)
		$res["descripcion"] = "<span class='tipo'>SIN DESCRIPCION</span>";
        echo '<tr>
				<td>'.$res["tipo"].'</td>
				<td>' . date("d/m/Y", strtotime($res["fecha"])) . '</td>
				<td class="oculto">' . $res["descripcion"] . '</td>
				<td class="oculto"> <a class="material-icons btn1">attachment</a></td>
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
