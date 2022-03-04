<?php
session_start();
$id_prenomina = $_SESSION["id_prenomina"];
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Descuento WHERE id_prenomina =".$id_prenomina." AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="titulo">Descuentos</th>
					<th class="titulo">Motivo</th>
					<th class="col-puesto">Archivo</th>
					<th class="titulo">Detalle</th>
					<th class="titulo">Eliminar</th>
				</thead>
				<tbody>';
	$i = 1;
    while ($res = mysqli_fetch_array($consulta)) {
        echo '<tr>
				<td>' . $res["dias"] . '</td>
				<td class="text-left">' . $res["motivo"] . '</td>
				<td class="col-puesto"> <a class="material-icons btn1" onclick="archivo(' . $res[0] . ',\'' . $res["url"] . '\',\'' . $res["RFC"] . '\',\'Descuento\',0)">attachment</a></td>
				<td> <a class="material-icons btn1"  onclick="detalle_descuento('.$res[0].')" >visibility</a></td>
				<td> <a class="material-icons btn1"  onclick="borrar_descuento('.$res[0].')">delete</a></td>
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
