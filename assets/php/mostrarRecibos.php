<?php
include "conexion.php";
setlocale(LC_ALL, "spanish");
$conexion = conexion();
$id = $_POST['id'];
$ano = $_POST["ano"];

$sql = "SELECT *,
(SELECT nombre FROM Periodo WHERE id_periodo = Archivo.id_periodo) AS periodo
FROM Archivo WHERE RFC = '" . $id . "' AND YEAR(del) = " . $ano;
$resultado = $conexion->query($sql);

if (mysqli_num_rows($resultado) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
						<th>Periodo</th>
						<th>Fecha</th>
						<th>Días pagados</th>
						<th>Descargar</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_array($resultado)) {
        $del = strftime("%d %b", strtotime($res["del"]));
        $al = strftime("%d %b", strtotime($res["al"]));
        echo '<tr>
				<td>' . $res["periodo"] . '</td>
				<td>' . $del . ' al ' . $al . '</td>
				<td>' . $res["dias_pago"].'</td>
				<td> <a class="material-icons btn1" href="assets/nominas/' . $res["url"] . '" download>cloud_download</a></td>
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
