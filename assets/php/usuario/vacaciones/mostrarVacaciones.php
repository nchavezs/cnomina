<?php
session_start();
include "../../conexion.php";
$conexion = conexion();
$id = $_SESSION["usuario"];
$ano = $_POST["ano"];

$sql = "SELECT * FROM Vacacion WHERE YEAR(del) = " . $ano . " AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) > 0) {
    $html = '<div class="table-responsive">
				<table class="table">
					<thead class=" text-primary">
						<th class="oculto">Dias</th>
						<th class="titulo">Fecha del</th>
						<th class="titulo">Fecha al</th>
						<th class="titulo">Archivo</th>
						<th class="titulo">Detalle</th>
					</thead>
					<tbody>';
    while ($res = mysqli_fetch_array($consulta)) {
        $html = $html . '<tr>
				<td class="oculto">' . $res["dias"] . '</td>
				<td>' . date("d/m/Y", strtotime($res["del"])) . '</td>
				<td>' . date("d/m/Y", strtotime($res["al"])) . '</td>
				<td> <a class="material-icons btn1" onclick="show_archivo(\'' . $res["url"] . '\')">attachment</a></td>
				<td> <a class="material-icons btn1" onclick="detalle_vacacion(\'' . $res[0] . '\')" >visibility</a></td>
			</tr>';
    }
    $html = $html . '</tbody>
					</table>
				</div>
			</div>
		</div>';
} else {
    $html = '<div class="table-responsive">
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

$sql = "SELECT SUM(dias) FROM Vacacion WHERE RFC = '" . $id . "' AND YEAR(del) = " . $ano;
$consulta = $conexion->query($sql);
$total = mysqli_fetch_row($consulta);
$total = $total[0];
if ($total == null) {
    $total = 0;
}


$datos["html"] = $html;
$datos["total"] = 'Dias de vacaciones: ' . $total;
echo json_encode($datos);
$conexion->close();
