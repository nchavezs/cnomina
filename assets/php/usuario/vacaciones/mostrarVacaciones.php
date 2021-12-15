<?php
session_start();
include "../../conexion.php";
$conexion = conexion();
$id = $_SESSION["usuario"];
$ano = $_POST["ano"];

$sql = "SELECT * FROM Vacacion WHERE YEAR(del) = " . $ano . " AND RFC = '" . $id."'";
$consulta = mysqli_query($conexion, $sql);

if (mysqli_num_rows($consulta) > 0) {
    $datos["html"] = '<div class="table-responsive">
						<table class="table">
							<thead class=" text-primary">
								<th class="col-puesto">Dias</th>
								<th class="titulo">Fecha del</th>
								<th class="titulo">Fecha al</th>
								<th class="titulo">Archivo</th>
								<th class="titulo">Detalle</th>
							</thead>
							<tbody>';
    while ($res = mysqli_fetch_row($consulta)) {
        $datos["html"] = $datos["html"] . '<tr>
											<td class="col-puesto">' . $res[3] . '</td>
											<td>' . date("d/m/Y", strtotime($res[4])) . '</td>
											<td>' . date("d/m/Y", strtotime($res[5])) . '</td>
											<td> <a class="material-icons btn1" id="' . $res[7] . '" onclick="archivo(this.id)">attachment</a></td>
											<td> <a class="material-icons btn1" id="' . $res[0] . '-" onclick="detalle_vacacion(this.id)" >visibility</a></td>
										</tr>';
    }
    $datos["html"] = $datos["html"] . '</tbody>
									</table>
								</div>
							</div>
						</div>';
} else {
    $datos["html"] = '<div class="table-responsive">
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
$consulta = mysqli_query($conexion, $sql);
$total = mysqli_fetch_row($consulta);
$total = $total[0];
if ($total == null) {
    $total = 0;
}

$datos["total"] = 'Dias de vacaciones: ' . $total;
echo json_encode($datos);
mysqli_close($conexion);
