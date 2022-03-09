<?php
include "../../conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION["usuario"];
$ano = $_POST["ano"];
$mes = $_POST["mes"];
$categoria = $_POST["categoria"];

$sql = "SELECT * FROM Pase WHERE YEAR(fecha) = " . $ano . " AND MONTH(fecha) = " . $mes . " AND categoria = " . $categoria . " AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

if ($consulta && (mysqli_num_rows($consulta) > 0)) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="titulo">Fecha</th>
					<th class="titulo">Hora</th>
					<th class="oculto">Archivo</th>
					<th class="titulo">Detalle</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_row($consulta)) {
        echo '<tr>
				<td>' . date("d/m/Y", strtotime($res[2])) . '</td>
				<td>' . $res[3] . '</td>
				<td class="oculto"> <a class="material-icons btn1" id="' . $res[6] . '" onclick="archivo(this.id)">attachment</a></td>
				<td> <a class="material-icons btn1" id="' . $res[0] . '-" onclick="detalle_pase(this.id)" >visibility</a></td>
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
