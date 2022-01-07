<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$ano = $_POST["ano"];

$sql = "SELECT * FROM Movimiento WHERE YEAR(fecha) = " . $ano . " AND RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="titulo">Fecha</th>
					<th class="titulo">Detalle</th>
					<th class="titulo">Formato</th>
					<th class="titulo">Archivo</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_row($consulta)) {
        echo '<tr>
				<td>' . date("d/m/Y", strtotime($res[2])) . '</td>
				<td> <a class="material-icons btn1"  id="' . $res[0] . '-" onclick="detalle_movimiento(this.id)">visibility</a></td>
				<td> <a class="material-icons btn1" onclick="formato_movimiento(' . $res[0] . ')">play_for_work</a></td>
				<td class="col-puesto"> <a class="material-icons btn1" onclick="archivo(' . $res[0] . ',\'' . $res[5] . '\',\'' . $res[1] . '\',\'Movimiento\',0)">attachment</a></td>
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



// <th class="titulo">Eliminar</th>
// <td> <a class="material-icons btn1" id="' . $res[0] . '" onclick="borrar_movimiento(this.id)">delete</a></td>
